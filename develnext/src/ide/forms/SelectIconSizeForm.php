<?php
namespace ide\forms;
use ide\forms\mixins\DialogFormMixin;
use ide\ui\FlowListViewDecorator;
use ide\ui\ImageBox;
use php\gui\event\UXMouseEvent;
use php\gui\layout\UXScrollPane;
use php\gui\UXDialog;
use php\gui\UXHyperlink;
use php\gui\UXImage;
use php\gui\UXLabel;

/**
 * Class SelectIconSizeForm
 * @package ide\forms
 *
 * @property UXScrollPane $list
 * @property UXHyperlink $link
 */
class SelectIconSizeForm extends AbstractIdeForm
{
    use DialogFormMixin;

    /**
     * @var FlowListViewDecorator
     */
    protected $listDecorator;

    public function init()
    {
        parent::init();

        $this->watch('focused', function ($self, $prop, $old, $new) {
            if (!$new) {
                $this->hide();
            }
        });

        $this->listDecorator = new FlowListViewDecorator($this->list->content);
        $this->listDecorator->setMultipleSelection(false);
    }

    public function setPack($name, $url, $licence)
    {
        $this->link->text = $name;
        $this->link->tooltipText = "Лицензия: $licence";

        if ($url) {
            $this->link->on('action', function () use ($url) {
                browse($url);
                $this->hide();
            });
        }
    }

    public function addSize($size, UXImage $image)
    {
        $node = new ImageBox(72, 72);
        $node->setTitle("{$size}x{$size}");
        $node->setImage($image);
        $node->data('size', $size);

        $node->on('click', function (UXMouseEvent $e) use ($size) {
            if ($e->clickCount > 1) {
                $this->setResult($size);
                $this->hide();
            }
        });

        $this->listDecorator->add($node);
    }

    /**
     * @event cancelButton.action
     */
    public function doCancel()
    {
        $this->setResult(null);
        $this->hide();
    }

    /**
     * @event applyButton.action
     */
    public function doApply()
    {
        $node = $this->listDecorator->getSelectionNode();

        if ($node) {
            $size = $node->data('size');

            $this->setResult($size);
            $this->hide();
        } else {
            UXDialog::show('Выберите нужный размер иконки');
        }
    }
}