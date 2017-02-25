<?php
namespace ide\editors;

use behaviour\custom\GameEntityBehaviour;
use ide\editors\common\ObjectListEditorItem;
use ide\editors\form\FormElementTypePane;
use ide\editors\form\IdePropertiesPane;
use ide\editors\form\IdeTabPane;
use ide\formats\form\AbstractFormDumper;
use ide\formats\form\AbstractFormElement;
use ide\formats\form\tags\AnchorPaneFormElementTag;
use ide\formats\GuiFormDumper;
use ide\Ide;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use php\game\UXSprite;
use php\game\UXSpriteView;

/**
 * Class GameSceneEditor
 * @package ide\editors
 */
class GameSceneEditor extends FormEditor
{
    /**
     * GameSceneEditor constructor.
     * @param string $file
     * @param AbstractFormDumper $dumper
     */
    public function __construct($file, AbstractFormDumper $dumper)
    {
        parent::__construct($file, $dumper);
    }

    public function makeLeftPaneUi()
    {
        $ui = new IdeTabPane();

        $this->objectTreeList = null;

        $this->propertiesPane = new IdePropertiesPane();
        $ui->addPropertiesPane($this->propertiesPane);

        return $ui;
    }

    protected function makeDesigner($fullArea = false)
    {
        $ui = parent::makeDesigner($fullArea);

        $this->designer->snapType = 'GRID';
        $this->designer->snapSize = 32;

        return $ui;
    }


    public function open()
    {
        $gui = GuiFrameworkProjectBehaviour::get();

        if ($gui) {
            $formEditors = $gui->getFormEditors();

            $arr = [];

            foreach ($formEditors as $editor) {
                $items = $editor->getObjectList();

                foreach ($items as $item) {
                    $behaviours = $editor->getBehaviourManager()->getBehaviours($item->value);

                    if ($behaviours[GameEntityBehaviour::class]) {
                        $result[$item->text] = $item->value;

                        $item->group = $editor->getTitle();
                        $item->sprite = $item->value;

                        $image = Ide::get()->getImage( $gui->getSpriteManager()->getSpritePreview($item->value));

                        if ($image) {
                            $image->preserveRatio = true;
                            $image->size = [16, 16];
                        }

                        $item->graphic = $image;

                        $arr[] = $item;
                    }
                }
            }

            $this->elementTypePane = new FormElementTypePane($arr);
            $this->elementTypePane->resetConfigurable(get_class($this));

            $this->elementTypePaneContainer->content = $this->elementTypePane->getContent();
        }

        parent::open();
    }

    protected function createElement($element, $screenX, $screenY, $parent = null)
    {
        if ($element instanceof ObjectListEditorItem) {
            $gui = GuiFrameworkProjectBehaviour::get();


            $sprite = new UXSprite();
            $sprite->image = $gui->getSpriteManager()->getSpriteImage($element->sprite);

            $node = new UXSpriteView($sprite);

            $this->layout->add($node);

            $node->screenX = $screenX;
            $node->screenY = $screenY;

            $node = $this->registerNode($node);

            $element = $this->format->getFormElement($node);

            if ($element) {
                $element->refreshNode($node,$this->designer);
            }

            return $node;
        }
    }
}