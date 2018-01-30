<?php
namespace ide\webplatform\editors;

use ide\action\ActionEditor;
use ide\behaviour\IdeBehaviourManager;
use ide\editors\FormEditor;
use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\SourceEventManager;
use ide\webplatform\formats\form\AbstractWebElement;
use php\gui\UXNode;
use php\lib\fs;

/**
 * Class WebFormEditor
 * @package ide\webplatform\editors
 */
class WebFormEditor extends FormEditor
{
    /**
     * @var string
     */
    private $frmFile;

    public function __construct($file, AbstractFormDumper $dumper)
    {
        $this->file = $file;
        $this->formDumper = $dumper;

        $this->eventManager = new SourceEventManager($file);

        $this->codeFile = $file;
        $this->frmFile = "$file.frm";

        $this->initCodeEditor($this->codeFile);

        $this->actionEditor = new ActionEditor($file . '.axml');
        $this->actionEditor->setFormEditor($this);

        $this->behaviourManager = new IdeBehaviourManager(fs::pathNoExt($file) . '.behaviour', function ($targetId, $has = false) {
            $node = $targetId ? $this->layout->lookup("#$targetId") : $this;

            if (!$node) {
                return null;
            }

            if ($has) {
                return true;
            }

            return $this->getFormat()->getFormElement($node);
        });
    }

    private $data = [];

    public function getRefactorRenameNodeType()
    {
        return parent::getRefactorRenameNodeType();
    }

    protected function makeDesigner($fullArea = false)
    {
        $designer = parent::makeDesigner($fullArea);

        $this->designer->onNodeResize(function (UXNode $node, $width, $height) {
            $node->{'webWidth'} = $width;
            $node->{'webHeight'} = $height;
        });

        return $designer;
    }


    /**
     * @param string $name
     * @param null $value
     * @return mixed
     */
    public function data(string $name, $value = null)
    {
        if (func_num_args() == 1) {
            return $this->data[$name];
        } else {
            $this->data[$name] = $value;
        }
    }

    /**
     * @return string
     */
    public function getFrmFile(): string
    {
        return $this->frmFile;
    }

    public function getModules()
    {
        return []; // TODO.
    }

    public function saveConfig()
    {
        // nop.
    }

    public function open($param = null)
    {
        $this->eachNode(function (UXNode $node, $nodeId, ?AbstractFormElement $element) {
            if ($element instanceof AbstractWebElement) {
                if (!$this->getDesigner()->isRegisteredNode($node)) {
                    $this->registerNode($node);
                }
            }
        });

        return parent::open($param);
    }

    /**
     *
     */
    public function load()
    {
        $this->trigger('load:before');

        $this->loaded = true;

        if ($this->factory) {
            $this->factory->reload();
        }

        $this->eventManager->load();
        $this->formDumper->load($this);

        $this->loadOthers();

        $this->layout->backgroundColor = 'white';

        $this->refreshInspectorType();

        $this->trigger('load:after');

        return true;
    }

}