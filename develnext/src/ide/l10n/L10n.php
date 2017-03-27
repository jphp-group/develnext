<?php
namespace ide\l10n;

use ide\Logger;
use php\gui\framework\DataUtils;
use php\gui\UXLabeled;
use php\gui\UXMenu;
use php\gui\UXMenuBar;
use php\gui\UXMenuItem;
use php\gui\UXNode;
use php\gui\UXParent;
use php\gui\UXTabPane;
use php\gui\UXTextInputControl;
use php\io\IOException;
use php\lib\str;
use php\util\Configuration;
use php\util\Regex;

/**
 * Class L10n
 * @package ide\l10n
 */
class L10n
{
    protected $language;
    protected $messages = [];

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @param string $path
     */
    public function putFile($path) {
        try {
            $config = new Configuration($path);
            $this->put($config->toArray());
        } catch (IOException $e) {
            Logger::exception("Cannot load $path, {$e->getMessage()}", $e);
        }
    }

    /**
     * @param array $messages
     */
    public function put(array $messages) {
        foreach ($messages as $code => $message) {
            $this->messages[$code] = $message;
        }
    }

    /**
     * @param string $code
     * @param array ...$args
     * @return string
     */
    public function get($code, ...$args)
    {
        $message = $this->messages[$code];

        if ($message) {
            return str::format($message, ...$args);
        }

        return $code;
    }

    public function translateNode(UXNode $node)
    {
        if ($node instanceof UXLabeled) {
            $node->text = $this->translate($node->text);
        } else if ($node instanceof UXTextInputControl) {
            $node->text = $this->translate($node->text);
            $node->promptText = $this->translate($node->promptText);
        } else if ($node instanceof UXMenuBar) {
            /** @var UXMenu $menu */
            foreach ($node->menus as $menu) {
                $this->translateMenu($menu);
            }
        } else if ($node instanceof UXTabPane) {
            foreach ($node->tabs as $tab) {
                $tab->text = $this->translate($tab->text);
            }
        }

        if ($node instanceof UXParent) {
            DataUtils::scanAll($node, function ($_, $node) {
                $this->translateNode($node);
            });
        }
    }

    protected function translateMenu(UXMenu $menu)
    {
        $menu->text = $this->translate($menu->text);

        foreach ($menu->items as $item) {
            if ($item instanceof UXMenuItem) {
                $item->text = $this->translate($item->text);
            } else if ($item instanceof UXMenu) {
                $this->translateMenu($item);
            }
        }
    }

    public function translate($text)
    {
        if (!str::contains($text, '{')) {
            return $text;
        }

        $regex = new Regex('(\\{.+\\})', '', $text);
        return $regex->replaceWithCallback(function (Regex $regex) {
            $text = $regex->group(1);
            return _(substr($text, 1, -1));
        });
    }
}