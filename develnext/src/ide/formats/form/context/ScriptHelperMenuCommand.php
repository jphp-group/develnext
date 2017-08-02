<?php
namespace ide\formats\form\context;

use ide\editors\AbstractEditor;
use ide\editors\FormEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\formats\AbstractFormFormat;
use ide\forms\ScriptHelperForm;
use ide\Ide;
use ide\library\IdeLibraryScriptGeneratorResource;
use php\gui\UXMenuItem;
use php\gui\UXNode;
use php\lib\arr;
use php\lib\items;
use php\lib\str;

class ScriptHelperMenuCommand extends AbstractMenuCommand
{
    protected $context;
    /**
     * @var array
     */
    private $model;

    /**
     * @var IdeLibraryScriptGeneratorResource
     */
    private $generators;

    /**
     * ScriptHelperMenuCommand constructor.
     * @param $context
     * @param array $model
     * @param array $generators
     */
    public function __construct($context, $model = [], array $generators = [])
    {
        $this->context = $context;
        $this->model = $model;
        $this->generators = $generators;
    }


    public function getName()
    {
        return 'Сгенерировать скрипт';
    }

    public function getAccelerator()
    {
        return 'F3';
    }


    public function getIcon()
    {
        return 'icons/scriptHelper16.png';
    }

    public function display($editor)
    {
        /** @var FormEditor $editor */
        $designer = $editor->getDesigner();

        /** @var UXNode $node */
        $node = arr::first($designer->getSelectedNodes());

        /** @var AbstractFormFormat $format */
        $format = $editor->getFormat();

        $element = $format->getFormElement($node ?: $editor);
        $nodeId = $editor->getNodeId($node);

        $model = [
            'object.id' => $nodeId,
            'object.x'  => $node ? $node->x : 0,
            'object.y'  => $node ? $node->y : 0,
            'object.width' => $node ? $node->width : 0,
            'object.height' => $node ? $node->height : 0,
            'object.text' => $node ? uiText($node) : '',
            'object.class' => arr::last(str::split($element->getElementClass(), '\\')),
            'type.name' => $element->getName(),
            'type.group' => $element->getGroup(),
            'type.class' => '\\' . $element->getElementClass(),
            'type.import' => $element->getElementClass(),
            'type.id' => str::format($element->getIdPattern(), ""),
            'type.width' => $element->getDefaultSize()[0],
            'type.height' => $element->getDefaultSize()[1],
            'source.class' => $editor->getEventManager()->getClassName(),
            'project.package' => Ide::project()->getPackageName()
        ];

        $model = $model + $this->model;

        $dlg = new ScriptHelperForm($this->context, $model, $nodeId ? '' : 'idEmpty');

        if ($this->generators) {
            $dlg->setResources($this->generators);
        } else {
            $dlg->setResources($element->getScriptGenerators());
        }

        $dlg->showDialog();
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $this->display($editor);
    }

    public function onBeforeShow(UXMenuItem $item, AbstractEditor $editor = null)
    {
        /** @var FormEditor $editor */
        $designer = $editor->getDesigner();
        $node = items::first($designer->getSelectedNodes());

        /*if ($node) {

        } else {*/
            //$item->disable = true;
        //}
    }
}