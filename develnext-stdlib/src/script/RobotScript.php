<?php
namespace script;

use behaviour\SetTextBehaviour;
use php\desktop\Robot;
use php\gui\framework\AbstractScript;
use php\gui\framework\behaviour\PositionableBehaviour;
use php\gui\framework\behaviour\TextableBehaviour;

/**
 * Class RobotScript
 * @package script
 */
class RobotScript extends AbstractScript implements PositionableBehaviour, SetTextBehaviour
{
    /**
     * @var Robot
     */
    protected $robot;

    /**
     * RobotScript constructor.
     */
    public function __construct()
    {
        if (class_exists(Robot::class)) {
            $this->robot = new Robot();
        }
    }

    /**
     * @param $target
     * @return mixed
     */
    protected function applyImpl($target)
    {
         if (!$this->robot) {
             $this->disabled = true;
         }
    }

    public function getX()
    {
        return $this->robot ? $this->robot->x : -1;
    }

    public function getY()
    {
        return $this->robot ? $this->robot->y : -1;
    }

    public function setX($x)
    {
        if ($this->robot) $this->robot->x = $x;
    }

    public function setY($y)
    {
        if ($this->robot) $this->robot->y = $y;
    }

    public function getPosition()
    {
        return [$this->getX(), $this->getY()];
    }

    public function setPosition(array $xy)
    {
        $this->setX($xy[0]);
        $this->setY($xy[1]);
    }

    public function mouseClick($button = 'PRIMARY')
    {
        if ($this->robot) $this->robot->mouseClick($button);
    }

    public function mouseDown($button = 'PRIMARY')
    {
        if ($this->robot) $this->robot->mouseDown($button);
    }

    public function mouseUp($button = 'PRIMARY')
    {
        if ($this->robot) $this->robot->mouseUp($button);
    }

    public function mouseScroll($wheelAmt)
    {
        if ($this->robot) $this->robot->mouseScroll($wheelAmt);
    }

    public function type($text)
    {
        if ($this->robot) $this->robot->type($text);
    }

    public function keyDown($keyCombination)
    {
        if ($this->robot) $this->robot->keyDown($keyCombination);
    }

    public function keyUp($keyCombination)
    {
        if ($this->robot) $this->robot->keyUp($keyCombination);
    }

    public function keyPress($keyCombination)
    {
        if ($this->robot) $this->robot->keyPress($keyCombination);
    }

    function setTextBehaviour($text)
    {
        $this->type($text);
    }

    function appendTextBehaviour($text)
    {
        $this->setTextBehaviour($text);
    }
}