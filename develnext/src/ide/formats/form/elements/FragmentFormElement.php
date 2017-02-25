<?php
namespace ide\formats\form\elements;

use develnext\lexer\inspector\entry\TypeEntry;
use develnext\lexer\inspector\entry\TypePropertyEntry;
use ide\formats\form\AbstractFormElement;
use ide\Ide;
use ide\Logger;
use ide\project\behaviours\GuiFrameworkProjectBehaviour;
use ide\project\Project;
use php\gui\designer\UXDesigner;
use php\gui\framework\AbstractForm;
use php\gui\framework\DataUtils;
use php\gui\layout\UXFragmentPane;
use php\gui\layout\UXVBox;
use php\gui\text\UXFont;
use php\gui\UXHyperlink;
use php\gui\UXLabel;
use php\gui\UXNode;

class FragmentFormElement extends AbstractFormElement
{
    public function isOrigin($any)
    {
        return $any instanceof UXFragmentPane;
    }

    public function getElementClass()
    {
        return UXFragmentPane::class;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Фрагмент формы';
    }

    public function getIcon()
    {
        return 'icons/card16.png';
    }

    public function getIdPattern()
    {
        return "fragment%s";
    }

    /**
     * @return UXNode
     */
    public function createElement()
    {
        $vbox = new UXFragmentPane();
        return $vbox;
    }

    public function getDefaultSize()
    {
        return [400, 200];
    }

    public function refreshNode(UXNode $node, UXDesigner $designer)
    {
        /** @var UXFragmentPane $node */
        parent::refreshNode($node, $designer);

        $node->alignment = 'CENTER';
        $node->backgroundColor = 'white';
        $node->style = '-fx-border-color: black; -fx-border-width: 1;';

        if (!$node->children->count || !($node->children[0] instanceof UXLabel)) {
            $label = new UXLabel();
            $node->children->add($label);

            $link = new UXHyperlink();
            $link->paddingLeft = 10;
            $node->children->add($link);
        } else {
            $label = $node->children[0];
            $link = $node->children[1];
        }

        $data = DataUtils::get($node);
        $form = $data->get('content');

        $label->graphic = Ide::get()->getImage($this->getIcon());

        if ($form) {
            $label->font = $label->font->withBold();
            $label->text = $form;

            if ($gui = GuiFrameworkProjectBehaviour::get()) {
                $editor = $gui->getFormEditor($form);

                if ($editor) {
                    $size = $editor->getLayout()->prefSize;
                    $link->text = "$size[0] x $size[1]";

                    $link->on('click', function () use ($size, $node, $designer) {
                        $node->size = $size;
                        waitAsync(100, function () use ($designer) {
                            $designer->update();
                        });
                    }, __CLASS__);
                } else {
                    Logger::warn("Cannot find form '$form'");
                }
            }
        } else {
            //$node->maxSize = $node->minSize = [-1, -1];
            $label->font = new UXFont($label->font->size, $label->font->family);
            $label->text = 'Фрагмент неизвестной формы';
        }
    }

    public function refreshInspector(UXNode $node, TypeEntry $type)
    {
        $type->properties['content'] = $t = new TypePropertyEntry();
        $t->name = 'content';

        $t->data['icon'] = 'icons/window16.png';
        $t->data['type'] = [AbstractForm::class];
        $t->data['description'] = 'Фрагмент формы';

        $gui = GuiFrameworkProjectBehaviour::get();

        if ($gui) {
            $data = DataUtils::get($node);
            $form = $data->get('content');

            if ($form && ($project = Ide::project())) {
                $t->data['type'] = ["{$project->getPackageName()}\\forms\\$form"];
            }
        }
    }
}