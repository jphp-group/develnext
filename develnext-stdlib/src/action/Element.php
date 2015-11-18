<?php
namespace action;

use behaviour\SetTextBehaviour;
use behaviour\StreamLoadableBehaviour;
use php\gui\UXApplication;
use php\gui\UXComboBox;
use php\gui\UXComboBoxBase;
use php\gui\UXForm;
use php\gui\UXImage;
use php\gui\UXImageArea;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\gui\UXLabeled;
use php\gui\UXListView;
use php\gui\UXTextInputControl;
use php\gui\UXWebView;
use php\io\IOException;
use php\io\Stream;
use php\lang\Thread;
use php\lib\Str;

/**
 * Class ObjectAction
 * @package action
 */
class Element
{
    static function setText($object, $value)
    {
        if ($object instanceof SetTextBehaviour) {
            $object->setTextBehaviour($value);
            return true;
        }

        if ($object instanceof UXLabeled || $object instanceof UXTextInputControl || $object instanceof UXImageArea) {
            $object->text = $value;
            return true;
        } elseif ($object instanceof UXComboBoxBase) {
            $object->value = $value;
            return true;
        } elseif ($object instanceof UXListView) {
            $object->items->clear();
            $object->items->addAll(Str::split($value, "\n"));
            return true;
        } elseif ($object instanceof UXForm) {
            $object->title = $value;
            return true;
        } elseif ($object instanceof UXWebView) {
            $object->engine->loadContent($value, "text/html");
            return true;
        }

        return false;
    }

    static function appendText($object, $value)
    {
        if ($object instanceof SetTextBehaviour) {
            $object->appendTextBehaviour($value);
            return true;
        }

        if ($object instanceof UXLabeled || $object instanceof UXTextInputControl || $object instanceof UXImageArea) {
            $object->text .= $value;
            return true;
        } else if ($object instanceof UXListView || $object instanceof UXComboBoxBase) {
            $object->items->add($value);
            return true;
        } else if ($object instanceof UXForm) {
            $object->title .= $value;
            return true;
        } else if ($object instanceof UXWebView) {
            $object->engine->loadContent($value, "text/html");
            return true;
        }

        return false;
    }

    static function loadContent($object, $path)
    {
        if ($object instanceof StreamLoadableBehaviour) {
            $content = $object->loadContentForObject($path);
            $object->applyContentToObject($content);
            return true;
        }

        if ($object instanceof UXImageView) {
            $object->image = new UXImage(Stream::of($path));
            return true;
        }

        if ($object instanceof UXWebView) {
            $object->engine->load($path);
            return true;
        }

        if (self::setText($object, '')) {
            try {
                $content = Stream::getContents($object, $path);
            } catch (IOException $e) {
                $content = null;
            }

            return self::setText($object, $content);
        }

        return false;
    }

    static function loadContentAsync($object, $path, callable $callback = null)
    {
        (new Thread(function () use ($object, $path, $callback) {
            if ($object instanceof StreamLoadableBehaviour) {
                $content = $object->loadContentForObject($path);

                UXApplication::runLater(function () use ($content, $object, $callback) {
                    $object->applyContentToObject($content);

                    if ($callback) $callback();
                });
            } else if ($object instanceof UXWebView) {
                UXApplication::runLater(function () use ($object, $path, $callback) {
                    $object->engine->load($path);

                    $object->engine->watchState(function () use ($object, $callback) {
                        if ($object->engine->state == 'SUCCEEDED') {
                            if ($callback) {
                                UXApplication::runLater($callback);
                            }
                        }
                    });
                });
            } else if ($object instanceof UXImageView) {
                $image = new UXImage(Stream::of($path));

                UXApplication::runLater(function () use ($image, $object, $callback) {
                    $object->image = $image;

                    if ($callback) $callback();
                });
            } else {
                if (Element::setText($object, '')) {
                    $content = Stream::getContents($path);

                    UXApplication::runLater(function () use ($content, $object, $callback) {
                        Element::setText($object, $content);

                        if ($callback) $callback();
                    });
                }
            }
        }))->start();
    }
}