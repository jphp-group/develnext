<?php
namespace ide\misc;

use ide\Ide;
use php\gui\UXButton;
use php\gui\UXMenuItem;

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

    abstract public function onExecute();

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

    public function makeGlyphButton()
    {
        $button = new UXButton();
        $button->tooltipText = $this->getName();

        if ($this->getAccelerator()) {
            $button->tooltipText .= ' (' . $this->getAccelerator() . ')';
        }

        $button->graphic = Ide::get()->getImage($this->getIcon());
        $button->css('cursor', 'hand');
        $button->padding = [4, 5];

        $button->on('action', function () {
            $this->onExecute();
        });

        return $button;
    }

    public function makeMenuItem()
    {
        $item = new UXMenuItem($this->getName());
        $item->graphic = Ide::get()->getImage($this->getIcon());
        $item->accelerator = $this->getAccelerator();

        $item->on('action', [$this, 'onExecute']);

        return $item;
    }

    public function makeUiForHead()
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

    /**
     * @param $name
     * @param $icon
     * @param callable $onExecute
     *
     * @return SimpleSingleCommand
     */
    public static function make($name, $icon, callable $onExecute)
    {
        return new SimpleSingleCommand($name, $icon, $onExecute);
    }

    public static function makeWithText($name, $icon, callable $onExecute)
    {
        $command = new SimpleSingleCommand($name, $icon, $onExecute);
        $command->setTextVisible(true);

        return $command;
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
     * ClosureCommand constructor.
     *
     * @param $name
     * @param $icon
     * @param $onExecute
     */
    public function __construct($name, $icon, callable $onExecute)
    {
        $this->name = $name;
        $this->icon = $icon;
        $this->onExecute = $onExecute;
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
        $button = $this->makeGlyphButton();

        if ($this->textVisible) {
            $button->text = $this->getName();
        }

        return $button;
    }

    public function onExecute()
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
}