<?php
namespace ide\editors\form;

use php\gui\framework\AbstractFactory;
use php\gui\framework\DataUtils;
use php\gui\UXData;
use php\gui\UXLoader;
use php\gui\UXNode;
use php\lang\IllegalArgumentException;

class IdeFormFactory extends AbstractFactory
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @param null|string $formName
     * @param null $path
     */
    public function __construct($formName, $path = null)
    {
        $this->factoryName = $formName;
        $this->file = $path;

        parent::__construct($path);
    }

    /**
     * @param string $id
     * @return UXNode
     * @throws Exception
     * @throws IllegalArgumentException
     */
    public function create($id)
    {
        if ($prototype = $this->prototypes[$id]) {
            $loader = new UXLoader();
            $node = $loader->loadFromString($prototype);
            $node->data('-factory', $this);
            $node->data('-factory-name', $this->factoryName);
            $node->data('-factory-id', $this->factoryName ? $this->factoryName . ".$id" : $id);

            $data = DataUtils::get($node);

            if ($_data = $this->prototypeData[$id]) {
                foreach ($_data->getAttributes() as $name => $value) {
                    $data->set($name, $value);
                }
            }

            //UXNodeWrapper::get($node)->applyData($data);

            //$this->eventBinder->loadBind($node, $id, __CLASS__, true);

            //$this->behaviourManager->applyForInstance($id, $node);

            /*uiLater(function () use ($node, $id) {
                $this->eventBinder->trigger($node, $id, 'create');
            });*/

            /*$node->observer('parent')->addListener(function ($old, $new) use ($node, $id) {
                if (!$new) {
                    $this->eventBinder->trigger($node, $id, 'destroy');
                }
            });*/

            $this->prototypeInstances[$id][] = $node;

            return $node;
        }

        return null;
    }

    public function addInstance(UXNode $node)
    {
        $id = $node->data('-factory-id');

        $this->prototypes[$id][] = $node;
    }

    public function reload()
    {
        $this->loadPrototypes($this->file);
    }
}