<?php
namespace ide\editors;

use Files;
use game\SpriteSpec;
use ide\editors\form\IdePropertiesPane;
use ide\editors\form\IdeSpritePane;
use ide\editors\form\IdeTabPane;
use ide\editors\sprite\IdeAnimationSpritePane;
use ide\editors\value\BooleanPropertyEditor;
use ide\editors\value\ElementPropertyEditor;
use ide\editors\value\EnumPropertyEditor;
use ide\editors\value\IntegerPropertyEditor;
use ide\forms\ImagePropertyEditorForm;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\misc\SimpleSingleCommand;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\ui\FlowListViewDecorator;
use ide\utils\FileUtils;
use ParseException;
use php\format\ProcessorException;
use php\game\UXSprite;
use php\gui\designer\UXDesignProperties;
use php\gui\event\UXEvent;
use php\gui\event\UXMouseEvent;
use php\gui\framework\Timer;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXFlowPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXPane;
use php\gui\layout\UXVBox;
use php\gui\paint\UXColor;
use php\gui\UXAlert;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXCanvas;
use php\gui\UXDialog;
use php\gui\UXForm;
use php\gui\UXImage;
use php\gui\UXImageArea;
use php\gui\UXLabel;
use php\gui\UXListView;
use php\gui\UXNode;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\io\File;
use php\io\IOException;
use php\lang\Thread;
use php\lib\Items;
use php\lib\Str;
use php\util\Regex;
use php\xml\DomElement;
use php\xml\XmlProcessor;

class GameSpriteEditor extends AbstractEditor
{
    /**
     * @var EnumPropertyEditor
     */
    protected $animationPropertyEditor;

    /**
     * @var UXDesignProperties
     */
    protected $designProperties;

    /**
     * @var FlowListViewDecorator
     */
    protected $framesPane;

    /**
     * @var UXListView
     */
    protected $animationList;

    /**
     * @var IdeAnimationSpritePane
     */
    protected $animationFramesPane;

    /**
     * @var SpriteSpec
     */
    protected $spec;

    /**
     * @var File[]
     */
    protected $frames;

    /**
     * @var IdeTabPane
     */
    protected $editorPane;

    /**
     * @var IdeSpritePane
     */
    protected $spritePane;

    /**
     * @var IdeAnimationSpritePane[]
     */
    protected $animationPanes;

    /**
     * @var UXTabPane
     */
    protected $tabPane;

    /**
     * @return File[]
     */
    protected function findFrameFiles()
    {
        $i = 0;

        $name = FileUtils::stripExtension($this->file);

        $files = [];

        while (true) {
            $file = File::of("$name/$i.png");

            if ($file->exists()) {
                $files[] = $file;
            } else {
                break;
            }

            $i++;
        }

        return $files;
    }

    protected function fixNames()
    {
        $i = 0;

        $name = FileUtils::stripExtension($this->file);

        $files = [];

        $gap = 0;

        while (true) {
            $file = File::of("$name/$i.png");

            if ($file->exists()) {
                $files[] = $file;
                $gap = 0;
            } else {
                $gap++;

                if ($gap > 300) {
                    break;
                }
            }

            $i++;
        }

        /**
         * @var int $i
         * @var File $file
         */
        foreach ($files as $i => $file) {
            $file->renameTo(new File("$name/$i.png"));
        }
    }

    /**
     *
     */
    public function load()
    {
        $xml = new XmlProcessor();

        UXApplication::runLater(function () {
            $this->designProperties->target = $this->spec;
            $this->designProperties->update();

            $this->updateUi();
        });

        try {
            $document = $xml->parse(FileUtils::get($this->file));
            $this->spec = new SpriteSpec($this->file, $document->find("/sprite"));

            return;
        } catch (IOException $e) {
            $this->spec = new SpriteSpec($this->file);
        } catch (ProcessorException $e) {
            Ide::toast("Некорректный файл '{$this->file}' - нет возможности его прочитать, будет создан новый");
            $this->spec = new SpriteSpec($this->file);
        }

        if (!$this->spec->frameWidth) {
            $this->spec->frameWidth = 32;
        }

        if (!$this->spec->frameHeight) {
            $this->spec->frameHeight = 32;
        }
    }

    public function save()
    {
        $files = $this->findFrameFiles();

        $canvas = new UXCanvas();
        $canvas->size = [$this->spec->frameWidth * sizeof($files), $this->spec->frameHeight];

        $gc = $canvas->getGraphicsContext();
        $gc->setFillColor(null);
        $gc->clearRect(0, 0, $canvas->size[0], $canvas->size[1]);

        foreach ($files as $i => $file) {
            $image = new UXImage($file);

            $x = 0;
            $y = 0;

            $width = $this->spec->frameWidth;
            $height = $this->spec->frameHeight;
            $frameWidth = $this->spec->frameWidth;
            $frameHeight = $this->spec->frameHeight;

            $resized = false;

            if ($this->spec->metaAutoSize
                && ($image->width > $this->spec->frameWidth || $image->height > $this->spec->frameHeight)) {
                $width = $image->width;
                $height = $image->height;

                $percent = false;

                if ($height > $width) {
                    $percent = (($frameHeight * 100) / $height) / 100;
                } elseif ($width > $height) {
                    $percent = (($frameWidth * 100) / $width) / 100;
                }

                if ($percent) {
                    $frameWidth = round($width * $percent);
                    $frameHeight = round($height * $percent);
                }

                $resized = true;
            }

            if ($this->spec->metaCentred) {
                if ($resized) {
                    $x = round($this->spec->frameWidth / 2 - $frameWidth / 2);
                    $y = round($this->spec->frameHeight / 2 - $frameHeight / 2);
                } else {
                    $x = round($this->spec->frameWidth / 2 - $image->width / 2);
                    $y = round($this->spec->frameHeight / 2 - $image->height / 2);
                }
            }

            $gc->drawImage(
                $image,

                0, 0,
                $width, $height,

                $i * $this->spec->frameWidth + $x, $y,
                $frameWidth, $frameHeight
            );
        }

        $name = FileUtils::stripExtension($this->file);

        $canvas->writeImageAsync("png", "$name.png", null, function ($success) use ($name) {
            if (!$success) {
                UXDialog::show("Невозможно записать файл $name.png");
            }
        });

        // ---

        $project = Ide::project();

        if ($project) {
            $this->spec->file = $project->getAbsoluteFile("$name.png")->getRelativePath('src');
        }

        $xml = new XmlProcessor();

        try {
            $document = $xml->createDocument();

            /** @var DomElement $root */
            $root = $document->createElement('sprite');
            $document->appendChild($root);

            $root->setAttributes([
                'file' => $this->spec->file,
                'frameWidth' => $this->spec->frameWidth,
                'frameHeight' => $this->spec->frameHeight,
                'speed' => $this->spec->speed,
                'defaultAnimation' => $this->spec->defaultAnimation,
                'metaCentred' => $this->spec->metaCentred,
                'metaAutoSize' => $this->spec->metaAutoSize,
            ]);

            foreach ($this->spec->animations as $name => $indexes) {
                $root->appendChild($document->createElement('animation', [
                    '@name' => $name,
                    '@indexes' => Str::join((array)$indexes, ',')
                ]));
            }

            FileUtils::put($this->file, $xml->format($document));
        } catch (IOException $e) {
            Ide::toast("Невозможно сохранить файл {$this->file}");
        }
    }

    public function hide()
    {
        parent::hide();

        $this->save();
    }

    public function createProperties(UXDesignProperties $properties)
    {
        $properties->addGroup('general', 'Кадры');
        $properties->addGroup('animation', 'Анимация');

        $setter = function (ElementPropertyEditor $editor, $value) {
            $this->spec->{$editor->code} = $value;

            $this->updateUi();
            $this->save();

            $project = Ide::project();

            if ($project && $project->hasBehaviour(GuiFrameworkProjectBehaviour::class)) {
                /** @var GuiFrameworkProjectBehaviour $behaviour */
                $behaviour = $project->getBehaviour(GuiFrameworkProjectBehaviour::class);

                $behaviour->getSpriteManager()->reloadAll();
            }
        };

        $this->animationPropertyEditor = $animationPropertyEditor = new EnumPropertyEditor();

        $properties->addProperty('general', 'frameWidth', 'Ширина кадра', new IntegerPropertyEditor(null, $setter));
        $properties->addProperty('general', 'frameHeight', 'Высота кадра', new IntegerPropertyEditor(null, $setter));
        $properties->addProperty('general', 'metaAutoSize', 'Авторазмер', new BooleanPropertyEditor(null, $setter));
        $properties->addProperty('general', 'metaCentred', 'По центру', new BooleanPropertyEditor(null, $setter));

        $properties->addProperty('animation', 'defaultAnimation', 'Главная анимация', $animationPropertyEditor);
        $properties->addProperty('animation', 'speed', 'Скорость анимации', new IntegerPropertyEditor());
    }

    /**
     * @param $file
     * @param $index
     * @param FlowListViewDecorator $framesPane
     */
    public function addFrameFromFileToUi($file, $index, FlowListViewDecorator $framesPane = null)
    {
        $area = new UXImageArea(new UXImage($file));
        $area->size = [$this->spec->frameWidth, $this->spec->frameHeight];
        $area->stretch = $this->spec->metaAutoSize;
        $area->smartStretch = $area->stretch;
        $area->centered = $this->spec->metaCentred;
        $area->proportional = true;

        $area->classes->add('preview');

        $border = new UXVBox();
        $border->padding = 5;
        $border->style = '-fx-border-width: 1px; -fx-border-color: silver; -fx-border-style: dashed;';
        $border->add($area);

        $label = new UXLabel($index);
        $label->alignment = 'CENTER';
        $label->maxWidth = 10000;
        $label->classes->add('title');

        $item = new UXVBox([$border, $label]);
        $item->fillWidth = true;
        $item->spacing = 5;
        $item->userData = $index;

        $item->data('file', $file);

        if ($framesPane == null) {
            $this->framesPane->add($item);
        } else {
            $framesPane->add($item);
        }
    }

    public function makeLeftPaneUi()
    {
        $ui = new IdeTabPane();

        $this->spritePane = new IdeSpritePane();

        $this->designProperties = $properties = new UXDesignProperties();
        $this->createProperties($properties);
        //$this->element->createProperties($properties);

        $pane = new IdePropertiesPane();
        $ui->addPropertiesPane($pane);

        $pane->setProperties($properties);
        $pane->update($this->spec);

        /*$pane->addCustomNode(new UXVBox([
            $this->makeAnimationsUi(),
            //$this->makeDisplaySpriteUi(),
        ])); */

        // $pane->addCustomNode($this->spritePane->makeUi());

        return $this->editorPane = $ui;
    }

    /**
     * @return UXNode
     */
    public function makeUi()
    {
        $animationFramesPane = new IdeAnimationSpritePane($this->spec, '');
        $animationFramesPane->getUi()->minHeight = 300;
        $animationFramesPane->getUi()->padding = 0;

        $animationFramesPane->on('add', function ($index, FlowListViewDecorator $list) {
            $files = $this->findFrameFiles();

            if ($files[$index]) {
                $this->addFrameFromFileToUi($files[$index], $index, $list);
            }
        });

        $animationFramesPane->on('change', function () {
            $this->updateAnimationPane();

            $this->save();
        });

        $this->animationFramesPane = $animationFramesPane;

        $ui = new UXVBox();
        $ui->spacing = 10;
        $ui->padding = 10;

        $actionPane = new UXHBox([
            $this->makeAddFrameButton(),
            $this->makeClearButton(),
        ]);
        $actionPane->fillHeight = true;
        $actionPane->spacing = 5;

        $framesPane = new FlowListViewDecorator(new UXFlowPane());
        $framesPane->setEmptyListText('Список кадров пуст.');
        $framesPane->on('remove', function (array $nodes) {
            $indexes = [];

            foreach ($nodes as $node) {
                $file = $node->data('file');
                File::of($file)->delete();

                $indexes[] = $node->userData;
            }

            $this->spec->removeFromAnimations($indexes);

            $this->fixNames();
            $this->save();

            $this->updateUi();
        });
        $framesPane->on('move', [$this, 'doMove']);

        UXVBox::setVgrow($framesPane->getPane(), 'ALWAYS');
        $framesPane->getPane()->maxHeight = 9999;

        $ui->add($animationFramesPane->getUi());
        $ui->add($actionPane);
        $ui->add($framesPane->getPane());;

        $this->framesPane = $framesPane;
        $this->framesPane->addMenuCommand(SimpleSingleCommand::makeWithText('Добавить в анимацию', 'icons/plus16.png', function () {
            $this->animationFramesPane->doAppend(-1, $this->framesPane->getSelectionIndexes());
        }));

        return $ui;
    }

    protected function makeDisplaySpriteUi()
    {
        $canvas = new UXCanvas();

        return $canvas;
    }

    protected function makeAddFrameButton()
    {
        $button = new UXButton('Добавить кадр(ы)');
        $button->style = '-fx-font-weight: bold;';
        $button->graphic = ico('imagesAdd16');
        $button->maxHeight = 9999;

        $button->on('action', [$this, 'doAddFrame']);

        return $button;
    }

    protected function makeClearButton()
    {
        $button = new UXButton('Очистить');
        $button->maxHeight = 9999;

        $button->on('action', [$this, 'doClear']);

        return $button;
    }

    public function showAnimation($name)
    {
        foreach ($this->tabPane->tabs as $tab) {
            if ($tab->userData == $name) {
                $this->tabPane->selectTab($tab);
                break;
            }
        }
    }

    protected function updateAnimationPane()
    {
        $variants = ['' => '[Нет]'];

        foreach ($this->spec->animations as $name => $_) {
            $variants[$name] = $name;
        }

        $defaultAnimation = $this->spec->defaultAnimation;
        $this->animationPropertyEditor->setVariants($variants);
        $this->animationPropertyEditor->applyValue($defaultAnimation);
    }

    public function updateUi()
    {
        /**
         * ...
         */
        $this->framesPane->clear();

        $files = $this->findFrameFiles();

        foreach ($files as $i => $file) {
            $this->addFrameFromFileToUi($file, $i);
        }

        if ($this->animationFramesPane) {
            $this->animationFramesPane->update();

            if (!$this->animationFramesPane->getAnimation()) {
                $this->animationFramesPane->showAnimation(Items::firstKey($this->spec->animations));
            }
        }

        $this->updateAnimationPane();
    }

    public function doClear()
    {
        $dialog = new MessageBoxForm('Вы уверены, что хотите удалить все кадры и анимации спрайта?', ['yes' => 'Да, удалить все', 'no' => 'Нет']);

        if ($dialog->showDialog()) {
            if ($dialog->getResultIndex() == 0) {
                foreach ($this->findFrameFiles() as $file) {
                    if (!$file->delete()) {
                        UXDialog::show("Невозможно удалить файл $file", 'ERROR');
                    }
                }

                $this->spec->animations = [];

                $this->updateUi();
                $this->save();
            }
        }
    }

    public function doMove()
    {
        $files = [];

        /** @var UXPane $node */
        foreach ($this->framesPane->getChildren() as $i => $node) {
            $file = File::of($node->data('file'));

            if ($file->exists()) {
                $newName = $file->getParent() . "/tmp.$i.png";
                Files::delete($newName);

                $files[] = $newName;

                $file->renameTo($newName);
            }

            $title = $node->lookup('.title');

            if ($title instanceof UXLabel) {
                $title->text = $i;
            }
        }

        foreach ($files as $i => $name) {
            $file = File::of($name);

            $newName = $file->getParent() . "/$i.png";

            Files::delete($newName);

            $file->renameTo($newName);
        }

        $this->animationFramesPane->update();
        $this->save();
    }

    public function doAddFrame()
    {
        /** @var ImagePropertyEditorForm $dialog */
        $dialog = Ide::get()->getForm('ImagePropertyEditorForm');
        $dialog->title = 'Добавление кадра(ов)';

        if ($dialog->showDialog()) {
            $files = $this->findFrameFiles();

            $result = $dialog->getResult();

            $project = Ide::project();

            if ($result && $project) {
                $result = $project->getFile("src/$result");

                $image = new UXImage($result);

                $multiple = false;

                if ($image->width >= $this->spec->frameWidth * 2 || $image->height >= $this->spec->frameHeight * 2) {
                    $dialog = new MessageBoxForm(
                        "Изображение похоже на спрайт с несколькими кадрами, хотите чтобы изображение было разрезано на кадры ({$this->spec->frameWidth}x{$this->spec->frameHeight})?",
                        ['Да, разрезать на кадры', 'Нет, загрузить оригинал']
                    );

                    if ($dialog->showDialog() && $dialog->getResultIndex() == 0) {
                        $multiple = true;
                    }
                }

                $fileName = FileUtils::stripExtension($this->file);

                if (!$multiple) {
                    $dir = FileUtils::stripExtension($this->file);

                    $file = $project->copyFile($result, $project->getAbsoluteFile($dir)->getRelativePath());

                    $file->renameTo($dir . "/" . sizeof($files) . ".png");

                    $this->updateUi();
                    $this->save();
                } else {
                    // Проверяем, есть ли на изображении пиксели с альфа прозрачностью.

                    $alphaExists = false;

                    for ($i = 0; $i < $this->spec->frameWidth; $i++) {
                        for ($j = 0; $j < $this->spec->frameHeight; $j++) {
                            $color = $image->getPixelColor($i, $j);

                            if ($color->opacity < 0.999) {
                                $alphaExists = true;
                                break 2;
                            }
                        }
                    }

                    $transparentColor = null;

                    if (!$alphaExists) {
                        $transparentColor = $image->getPixelColor(0, 0);
                    }

                    $offset = sizeof($files);

                    $sprite = new UXSprite();
                    $sprite->image = $image;
                    $sprite->frameWidth = $this->spec->frameWidth;
                    $sprite->frameHeight = $this->spec->frameHeight;

                    /** @var UXCanvas[] $canvasFrames */
                    $canvasFrames = [];

                    for ($i = 0; $i < $sprite->frameCount; $i++) {
                        $canvas = new UXCanvas();
                        $canvas->size = $sprite->frameSize;

                        $sprite->draw($canvas, $i);

                        $canvasFrames[] = $canvas;
                    }

                    $done = sizeof($canvasFrames);

                    Ide::get()->getMainForm()->showPreloader('Импортируем кадры ...');

                    Files::makeDirs($fileName);

                    foreach ($canvasFrames as $i => $canvas) {
                        $canvas->writeImageAsync('png', $fileName . "/" . ($i + $offset) . ".png", $transparentColor, function () use (&$done) {
                            $done--;

                            if ($done <= 0) {
                                $this->updateUi();
                                $this->save();

                                Ide::get()->getMainForm()->hidePreloader();
                            }
                        });
                    }
                }
            }
        }
    }
}