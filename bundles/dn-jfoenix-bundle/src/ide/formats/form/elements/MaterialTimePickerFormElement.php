<?php
namespace ide\formats\form\elements;

use ide\formats\form\AbstractFormElement;
use php\gui\event\UXMouseEvent;
use php\gui\framework\DataUtils;
use php\gui\UXColorPicker;
use php\gui\UXMaterialButton;
use php\gui\UXMaterialColorPicker;
use php\gui\UXMaterialDatePicker;
use php\gui\UXMaterialTimePicker;
use php\gui\UXNode;
use php\gui\UXRating;
use php\gui\UXToggleSwitch;

class MaterialTimePickerFormElement extends AbstractFormElement
{
    public function getGroup()
    {
        return 'Material UI';
    }

    public function getName()
    {
        return 'Material Поле для времени';
    }

    public function getIcon()
    {
        return 'icons/develnext/bundle/jfoenix/timePicker16.png';
    }

    public function getElementClass()
    {
        return UXMaterialTimePicker::class;
    }

    public function isOrigin($any)
    {
        return $any instanceof UXMaterialTimePicker;
    }

    public function getIdPattern()
    {
        return "timeEdit%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        return new UXMaterialTimePicker();
    }


    public function registerNode(UXNode $node)
    {
        if ($node->parent) { // fix bug.
            /** @var UXMaterialTimePicker $node */
            $data = DataUtils::get($node);
            $format = $data->get('format');
            $value = $data->get('value');
            $hourView24 = $data->get('hourView24');

            uiLater(function () use ($format, $value, $hourView24, $node) {
                if ($format) {
                    $node->format = $format;
                }

                if ($value) {
                    $node->value = $value;
                }

                if ($hourView24) {
                    $node->hourView24 = true;
                }
            });
        }
    }


    public function getDefaultSize()
    {
        return [150, 35];
    }
}