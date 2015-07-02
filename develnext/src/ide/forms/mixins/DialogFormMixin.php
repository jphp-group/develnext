<?php
namespace ide\forms\mixins;

use php\gui\framework\AbstractForm;

trait DialogFormMixin
{
    protected $result;

    /**
     * @param $x
     * @param $y
     *
     * @return bool
     */
    public function showDialog($x = null, $y = null)
    {
        /** @var AbstractForm|DialogFormMixin $this */

        $this->centerOnScreen();

        if ($x !== null) {
            $this->x = $x;
        }

        if ($y !== null) {
            $this->y = $y;
        }

        $this->showAndWait();
        return $this->result !== null;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }
}