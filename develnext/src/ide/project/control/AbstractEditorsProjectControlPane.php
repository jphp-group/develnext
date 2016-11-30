<?php
namespace ide\project\control;

use ide\editors\AbstractEditor;
use ide\editors\menu\AbstractMenuCommand;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\systems\FileSystem;
use ide\ui\FlowListViewDecorator;
use ide\ui\ImageBox;
use php\gui\event\UXMouseEvent;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\gui\UXNode;
use php\gui\UXSeparator;
use php\lib\fs;

class AbstractEditorsProjectControlPaneEditCommand extends AbstractMenuCommand
{
    /**
     * @var AbstractEditorsProjectControlPane
     */
    protected $pane;

    /**
     * AbstractEditorsProjectControlPaneEditCommand constructor.
     * @param AbstractEditorsProjectControlPane $pane
     */
    public function __construct(AbstractEditorsProjectControlPane $pane)
    {
        $this->pane = $pane;
    }

    public function getName()
    {
        return 'Редактировать';
    }

    public function getIcon()
    {
        return 'icons/edit16.png';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $this->pane->doEdit();
    }
}

class AbstractEditorsProjectControlPaneCloneCommand extends AbstractMenuCommand
{
    /**
     * @var AbstractEditorsProjectControlPane
     */
    protected $pane;

    /**
     * AbstractEditorsProjectControlPaneEditCommand constructor.
     * @param AbstractEditorsProjectControlPane $pane
     */
    public function __construct(AbstractEditorsProjectControlPane $pane)
    {
        $this->pane = $pane;
    }

    public function getName()
    {
        return 'Клонировать';
    }

    public function getIcon()
    {
        return 'icons/copy16.png';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $this->pane->doClone();
    }
}

/**
 * Class AbstractEditorsProjectControlPane
 * @package ide\project\control
 */
abstract class AbstractEditorsProjectControlPane extends AbstractProjectControlPane
{
    /**
     * @var FlowListViewDecorator
     */
    protected $list;

    /**
     * @return mixed
     */
    abstract protected function doAdd();

    /**
     * @return mixed[]
     */
    abstract protected function getItems();

    /**
     * @return mixed
     */
    abstract protected function getBigIcon($item);


    public function doEdit()
    {
        if ($this->getSelectedItem() instanceof AbstractEditor) {
            FileSystem::open($this->getSelectedItem());
        }
    }

    public function doClone()
    {
        if ($this->getSelectedItem() instanceof AbstractEditor) {

            /** @var AbstractEditor $editor */
            $editor = $this->getSelectedItem();

            if ($editor->getFormat()->availableCreateDialog()) {
                $name = $editor->getFormat()->showCreateDialog();

                if ($name !== null) {
                    $ext = fs::ext($editor->getFile());
                    $newFile = fs::parent($editor->getFile()) . "/$name" . ($ext ? ".$ext" : "");

                    if (fs::exists($newFile))
                    {
                        UXDialog::showAndWait("Введенное имя '$name' уже занято, введите другое.", 'WARNING');
                        $this->doClone();
                        return;
                    }

                    $editor->getFormat()->duplicate($editor->getFile(), $newFile);
                    $this->refresh();
                }
            }
        }
    }

    /**
     * @return int
     */
    function getMenuCount()
    {
        return sizeof($this->getItems());
    }

    /**
     * @return mixed|null
     */
    public function getSelectedItem()
    {
        return $this->list->getSelectionNode() ? $this->list->getSelectionNode()->userData : null;
    }

    /**
     * @param AbstractEditor $item
     * @return UXNode
     */
    protected function makeItemUi($item)
    {
        $imageBox = new ImageBox(80, 48);
        $imageBox->userData = $item;
        $image = Ide::get()->getImage($this->getBigIcon($item));

        $imageBox->setImage($image ? $image->image : null);

        if (method_exists($item, 'getTitle')) {
            $imageBox->setTitle($item->getTitle());
        } else {
            $imageBox->setTitle($item->name);
        }

        return $imageBox;
    }

    /**
     * @return UXNode
     */
    protected function makeUi()
    {
        $pane = new FlowListViewDecorator();
        $pane->setEmptyListText($this->getName() . '. Список пуст.');
        $pane->setMultipleSelection(true);

        $pane->addMenuCommand(new AbstractEditorsProjectControlPaneEditCommand($this));
        $pane->addMenuCommand(new AbstractEditorsProjectControlPaneCloneCommand($this));

        $pane->on('beforeRemove', function (array $nodes) {
            foreach ($nodes as $node) {
                if ($node->userData instanceof AbstractEditor) {
                    $editor = $node->userData;
                    $format = $editor->getFormat();

                    $file = $editor->getFile();

                    if (!MessageBoxForm::confirmDelete($editor->getTitle(), $this->ui)) {
                        return true;
                    }

                    FileSystem::close($file);

                    if ($editor->delete($file) === false) {
                        return true;
                    }

                    waitAsync(1000, function () use ($editor, $file) {
                        $editor->delete($file);

                        $this->trigger('updateCount');

                        if (Ide::project()) {
                            Ide::project()->trigger('updateSettings');
                        }
                    });

                    if (fs::exists($file)) {
                        UXDialog::show('Ошибка удаления, что-то пошло не так', 'ERROR');
                        return true;
                    } else {
                        if ($project = Ide::project()) {
                            $project->update();
                        }
                    }
                }
            }

            return false;
        });

        $this->list = $pane;


        $pane = $pane->getPane();
        UXVBox::setVgrow($pane, 'ALWAYS');


        $ui = new UXVBox([$this->makeActionsUi()]);

        foreach ($this->makeAdditionalUi() as $one) {
            $ui->add($one);
        }

        $ui->add($pane);
        $ui->spacing = 10;

        return $ui;
    }

    /**
     * @return array
     */
    protected function makeAdditionalUi()
    {
        return [];
    }

    protected function makeActionsUi()
    {
        $addButton = new UXButton('Добавить');
        $addButton->classes->add('icon-plus');
        $addButton->font = $addButton->font->withBold();
        $addButton->maxHeight = 999;
        $addButton->on('action', function () {
            $this->doAdd();
            $this->trigger('updateCount');
        });

        $editButton = new UXButton('Редактировать');
        $editButton->classes->add('icon-edit');
        $editButton->maxHeight = 999;
        $editButton->enabled = false;
        $editButton->on('action', function () {
            $this->doEdit();
        });

        $cloneButton = new UXButton('Клонировать');
        $cloneButton->classes->add('icon-copy');
        $cloneButton->maxHeight = 999;
        $cloneButton->enabled = false;
        $cloneButton->on('action', function () {
            $this->doClone();
        });

        $delButton = new UXButton();
        $delButton->classes->add('icon-trash2');
        $delButton->maxHeight = 999;
        $delButton->enabled = false;
        $delButton->text = 'Удалить';

        $delButton->on('action', function () {
            $this->list->removeBySelections();
        });

        $this->list->on('select', function ($nodes) use ($delButton, $editButton, $cloneButton) {
            $delButton->enabled = !!$nodes;
            $editButton->enabled = sizeof($nodes) == 1;
            $cloneButton->enabled = sizeof($nodes) == 1;
        });

        $ui = new UXHBox([$addButton, new UXSeparator('VERTICAL'), $editButton, $cloneButton, $delButton]);
        $ui->spacing = 5;
        $ui->minHeight = 30;

        return $ui;
    }

    /**
     * Refresh ui and pane.
     */
    public function refresh()
    {
        $node = $this->list->getSelectionNode();

        $selectedEditor = $node ? $node->userData : null;

        $this->list->clear();

        foreach ($this->getItems() as $editor) {
            $imageBox = $this->makeItemUi($editor);

            $imageBox->on('click', function (UXMouseEvent $e) use ($editor) {
                if ($e->clickCount > 1) {
                    FileSystem::open($editor->getFile());
                }
            });

            $this->list->add($imageBox);

            if ($editor->equals($selectedEditor)) {
                $this->list->setSelectionNodes([$imageBox]);
            }
        }
    }
}