<?php
namespace ide\editors;

use ide\editors\value\TextPropertyEditor;
use ide\formats\form\AbstractFormDumper;
use ide\formats\form\event\AbstractEventKind;
use php\gui\designer\UXDesignPane;
use php\gui\designer\UXDesignProperties;
use php\gui\UXNode;
use php\lib\items;
use php\util\Flow;

class FactoryEditor extends FormEditor
{
    public function __construct($file, AbstractFormDumper $dumper)
    {
        parent::__construct($file, $dumper);

        $this->on('updateNode:before', function ($node, UXDesignProperties $originProperties) {
            if (!($node instanceof UXNode)) {
                $this->leftPaneUi->hideBehaviourPane();
                $this->leftPaneUi->hideEventListPane();
            } else {
                $this->leftPaneUi->showEventListPane();
                $this->leftPaneUi->showBehaviourPane();

                $properties = new UXDesignProperties();
                $properties->addGroup('prototype', 'Прототип');

                $editor = new TextPropertyEditor();
                $editor->setAsVirtualProperty();
                $properties->addProperty('prototype', 'protoName', 'Название', $editor);

                $editor = new TextPropertyEditor();
                $editor->setAsVirtualProperty();
                $properties->addProperty('prototype', 'protoDescription', 'Описание', $editor);

                $this->propertiesPane->addProperties($properties);
            }
        });

        $this->on('updateNode:after', function ($node, UXDesignProperties $originProperties) {
            if (!($node instanceof UXNode)) {
                $properties = new UXDesignProperties();

                $this->propertiesPane->setProperties($properties);
            } else {
                $eventTypes = [
                    'create'  => AbstractEventKind::make(['code' => 'create', 'name' => 'Создание', 'kind' => 'ActionEvent', 'icon' => 'icons/idea16.png']),
                    'destroy' => AbstractEventKind::make(['code' => 'destroy', 'name' => 'Уничтожение', 'kind' => 'ActionEvent', 'icon' => 'icons/trash16.png'])
                ];

                $eventTypes = $eventTypes + $this->eventListPane->getEventTypes();
                $this->eventListPane->setEventTypes($eventTypes);
            }
        });

        $this->on('makeDesignPane', function (UXDesignPane $pane) {
            $pane->borderColor = 'silver';
        });

        $this->on('load:after', function () {
            $this->layout->backgroundColor = 'white';
        });
    }
}