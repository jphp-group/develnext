<?php
namespace ide\misc;

use ide\editors\AbstractEditor;
use ide\Ide;
use php\gui\event\UXMouseEvent;
use php\gui\UXButton;
use php\gui\UXMenuItem;
use php\gui\UXSeparator;

/**
 * Class AbstractCommand
 * @package ide\misc
 */
abstract class AbstractCommand
{
    /**
     * @var mixed
     */
    protected $target;

    abstract public function getName();

    abstract public function onExecute($e = null, AbstractEditor $editor = null);

    public function getUniqueId()
    {
        return get_class($this);
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return 10000;
    }

    public function withBeforeSeparator()
    {
        return false;
    }

    public function withAfterSeparator()
    {
        return false;
    }

    public function isAlways()
    {
        return false;
    }

    public function getIcon()
    {
        return null;
    }

    public function getAccelerator()
    {
        return null;
    }

    public function getCategory()
    {
        return 'project';
    }

    public function makeAction()
    {
        return function () {
            $this->onExecute();

            $project = Ide::project();

            if ($project) {
                $project->update();
            }
        };
    }

    public function makeGlyphButton()
    {
        $button = new UXButton();
        $button->tooltipText = $this->getName();

        if ($this->getAccelerator()) {
            $button->tooltipText .= ' (' . $this->getAccelerator() . ')';
        }

        $button->graphic = Ide::get()->getImage($this->getIcon());
        $button->maxHeight = 9999;
        $button->padding = 3;
        $button->paddingLeft = $button->paddingRight = 7;

        $action = $this->makeAction();
        $button->on('action', $action);

        return $button;
    }

    public function makeMenuItem()
    {
        $item = new UXMenuItem($this->getName());
        $item->graphic = Ide::get()->getImage($this->getIcon());
        $item->accelerator = $this->getAccelerator();

        $item->on('action', $this->makeAction());

        return $item;
    }

    public function makeUiForHead()
    {
        return null;
    }

    public function makeUiForRightHead()
    {
        return null;
    }

    /**
     * @param mixed $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    public static function makeSeparator()
    {
        return new SeparatorCommand();
    }

    /**
     * @param $name
     * @param $icon
     * @param callable $onExecute
     *
     * @param null $accelerator
     * @return SimpleSingleCommand
     */
    public static function make($name, $icon, callable $onExecute, $accelerator = null)
    {
        return new SimpleSingleCommand($name, $icon, $onExecute, $accelerator);
    }

    public static function makeForMenu($name, $icon, callable $onExecute, $accelerator = null)
    {
        $command = new SimpleSingleCommand($name, $icon, $onExecute, $accelerator);
        $command->setHeadVisible(false);

        return $command;
    }

    public static function makeWithText($name, $icon, callable $onExecute, $accelerator = null)
    {
        $command = new SimpleSingleCommand($name, $icon, $onExecute, $accelerator);
        $command->setTextVisible(true);

        return $command;
    }
}

class SeparatorCommand extends AbstractCommand
{
    public function getName()
    {
        return '';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
    }

    public function makeUiForHead()
    {
        $separator = new UXSeparator();
        $separator->orientation = 'VERTICAL';
        $separator->paddingLeft = 2;
        return $separator;
    }
}

class SimpleSingleCommand extends AbstractCommand
{
    protected $name, $icon, $onExecute;

    /**
     * @var bool
     */
    protected $textVisible = false;

    /**
     * @var bool
     */
    protected $headVisible = true;

    /**
     * @var string
     */
    protected $category = 'project';

    /**
     * @var bool
     */
    protected $always = false;

    protected $accelerator = null;

    /**
     * ClosureCommand constructor.
     *
     * @param $name
     * @param $icon
     * @param callable $onExecute
     * @param null $accelerator
     */
    public function __construct($name, $icon, callable $onExecute, $accelerator = null)
    {
        $this->name = $name;
        $this->icon = $icon;
        $this->onExecute = $onExecute;
        $this->accelerator = $accelerator;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function makeUiForHead()
    {
        if ($this->headVisible) {
            $button = $this->makeGlyphButton();
            $button->maxHeight = 9999;

            if ($this->textVisible) {
                $button->text = $this->getName();
            }

            return $button;
        } else {
            return null;
        }
    }

    public function getAccelerator()
    {
        return $this->accelerator;
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $onExecute = $this->onExecute;
        $onExecute($this->target);
    }

    /**
     * @param boolean $textVisible
     */
    public function setTextVisible($textVisible)
    {
        $this->textVisible = $textVisible;
    }

    /**
     * @param boolean $headVisible
     */
    public function setHeadVisible($headVisible)
    {
        $this->headVisible = $headVisible;
    }

    public function makeMenuItem()
    {
        return parent::makeMenuItem(); // TODO: Change the autogenerated stub
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return boolean
     */
    public function isAlways()
    {
        return $this->always;
    }

    /**
     * @param boolean $always
     */
    public function setAlways($always)
    {
        $this->always = $always;
    }
}