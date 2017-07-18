<?php
namespace ide\forms;

use behaviour\custom\EscapeShutdownBehaviour;
use ide\forms\mixins\DialogFormMixin;
use ide\forms\mixins\SavableFormMixin;
use ide\Ide;
use ide\library\IdeLibraryScriptGeneratorResource;
use ide\Logger;
use ide\ui\ListMenu;
use php\gui\event\UXMouseEvent;
use php\gui\layout\UXHBox;
use php\gui\layout\UXTilePane;
use php\gui\layout\UXVBox;
use php\gui\UXForm;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXTextField;
use php\lib\str;

/**
 * Class ScriptHelperForm
 * @package ide\forms
 *
 * @property UXImageView $icon
 * @property UXVBox $dialogContainer
 * @property UXListView $list
 * @property UXTextField $searchField
 */
class ScriptHelperForm extends AbstractIdeForm
{
    use DialogFormMixin;
    use SavableFormMixin;

    /**
     * @var string
     */
    protected $context;

    /**
     * @var array
     */
    protected $model;

    /**
     * @var string
     */
    private $param;

    /**
     * @var IdeLibraryScriptGeneratorResource[]
     */
    private $resources;

    /**
     * ScriptHelperForm constructor.
     * @param string $context
     * @param array $model
     * @param string $param
     */
    public function __construct($context, array $model = [], $param = '')
    {
        parent::__construct();

        Logger::info("Create script helper (param = $param)");

        $this->context = $context;
        $this->model = $model;

        new EscapeShutdownBehaviour($this);
        $this->param = $param;
    }

    public function init()
    {
        $this->icon->image = ico('scriptHelper32')->image;

        $this->dialogContainer->children->replace($this->list, $list = new ListMenu());
        $list->classes->remove('dn-list-menu');

        $list->id = 'list';
        $list->on('click', function (UXMouseEvent $e) {
            if ($e->isDoubleClick()) {
                $this->doGen();
            }
        });
        //$list->setThin(true);
        //$list->setNameThin(true);

        UXVBox::setVgrow($list, 'ALWAYS');
    }

    /**
     * @event showing
     */
    public function doShowing()
    {
        $this->doSearch();
    }

    /**
     * @event searchField.keyUp
     * @event searchBtn.action
     */
    public function doSearch()
    {
        $this->displayList($this->searchField->text);
    }

    /**
     * @event genButton.action
     */
    public function doGen()
    {
        /** @var IdeLibraryScriptGeneratorResource $resource */
        $resource = $this->list->selectedItem;

        if ($resource) {
            //$this->hide();

            $source = $resource->getSource($this->param);

            $model = $this->model;

            $model['script.name'] = $resource->getName();
            $model['script.desc'] = $resource->getDescription();
            $model['script.author'] = $resource->getAuthor();

            foreach ($this->model as $code => $value) {
                $source = str::replace($source, "#$code#", $value);
            }

            $form = new SourceCodePreviewForm($source, $resource->getSourceSyntax());
            $form->owner = $this;

            if ($form->showDialog() && $form->getResult()) {
                $this->hide();
            }
        }
    }

    /**
     * @event cancelButton.action
     */
    public function doCancel()
    {
        $this->hide();
    }

    /**
     * @param IdeLibraryScriptGeneratorResource[] $resources
     */
    public function setResources(array $resources)
    {
        $this->resources = $resources;
    }

    public function displayList($searchQuery = null)
    {
        $this->list->items->clear();

        foreach ($this->resources as $resource) {
            if (!$resource->getSource($this->param)) continue;

            if (!str::trim($searchQuery) || str::posIgnoreCase($resource->getName(), $searchQuery) > -1) {
                $this->list->items->add($resource);
            }
        }

        $resources = Ide::get()->getLibrary()->getResources('scriptGenerators');

        /** @var IdeLibraryScriptGeneratorResource $resource */
        foreach ($resources as $resource) {
            if (!$resource->hasContext($this->context)) continue;

            if (!$resource->getSource($this->param)) continue;

            if (!str::trim($searchQuery) || str::posIgnoreCase($resource->getName(), $searchQuery) > -1) {
                $this->list->items->add($resource);
            }
        }
    }
}