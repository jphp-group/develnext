<?php
namespace ide\editors\form;

use ide\Logger;
use php\game\UXSpriteView;
use php\gui\framework\AbstractFactory;
use php\gui\framework\DataUtils;
use php\gui\UXData;
use php\gui\UXImageArea;
use php\gui\UXLoader;
use php\gui\UXNode;
use php\lang\IllegalArgumentException;
use php\time\Time;

class IdeFormFactory extends AbstractFactory
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var UXLoader
     */
    protected $loader;

    /**
     * @param null|string $formName
     * @param null $path
     */
    public function __construct($formName, $path = null)
    {
        $this->factoryName = $formName;
        $this->file = $path;
        $this->loader = new UXLoader();

        parent::__construct($path);
    }

    protected function makeNode($id)
    {
        $element = $this->prototypeElements[$id];

        $node = null;
        $attrs = $element->getAttributes();

        switch ($element->getTagName()) {
            case 'SpriteView':
                $node = new UXSpriteView();
                $node->size = [$attrs['width'], $attrs['height']];
                break;
            case 'ImageViewEx':
                $node = new UXImageArea();
                $node->size = [$attrs['width'], $attrs['height']];
                $node->text = $attrs['text'];
                $node->textColor = $attrs['textFill'];
                $node->backgroundColor = $attrs['background'];

                foreach (['autoSize', 'proportional', 'stretch', 'centered', 'mosaic', 'mosaicGap'] as $prop) {
                    $node->{$prop} = $attrs[$prop] == 'true';
                }
                break;
        }

        if ($node != null) {
            $node->id = $attrs['id'];

            if (isset($attrs['opacity'])) {
                $node->opacity = $attrs['opacity'];
            }

            if (isset($attrs['rotate'])) {
                $node->rotate = $attrs['rotate'];
            }

            $node->focusTraversable = $attrs['focusTraversable'] == 'true';

            $node->position = [$attrs['layoutX'], $attrs['layoutY']];

            return $node;
        }

        return $this->loader->loadFromString($this->prototypes[$id]);
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
            $node = $this->makeNode($id);

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