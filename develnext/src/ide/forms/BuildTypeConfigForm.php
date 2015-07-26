<?php
namespace ide\forms;

use ide\build\AbstractBuildType;
use ide\forms\mixins\DialogFormMixin;
use ide\Ide;
use php\gui\framework\AbstractForm;
use php\gui\framework\DataUtils;
use php\gui\framework\GUI;
use php\gui\layout\UXAnchorPane;
use php\gui\UXApplication;
use php\gui\UXCheckbox;
use php\gui\UXDialog;
use php\gui\UXImageView;
use php\gui\UXLoader;
use php\gui\UXNode;
use php\io\Stream;
use php\lib\Str;

/**
 * Class BuildTypeConfigForm
 * @package ide\forms
 *
 * @property UXCheckbox $showConfigCheckbox
 * @property UXImageView $icon
 */
class BuildTypeConfigForm extends AbstractForm
{
    use DialogFormMixin;

    /**
     * @var string
     */
    private $innerForm;

    /**
     * @var UXAnchorPane
     */
    private $configLayout;

    /**
     * BuildTypeConfigForm constructor.
     *
     * @param string $innerForm
     */
    public function __construct($innerForm)
    {
        parent::__construct();

        $this->innerForm = $innerForm;

        $loader = new UXLoader();
        $path = static::DEFAULT_PATH . $innerForm;

        Stream::tryAccess($path, function (Stream $stream) use ($loader) {
            $layout = $loader->load($stream);
            $this->applyForm($layout);
        });
    }

    public function init()
    {
        $this->icon->image = Ide::get()->getImage('icons/windowConfig32.png')->image;
    }

    public function applyForm(UXNode $node)
    {
        if ($this->configLayout) {
            $this->remove($this->configLayout);
        }

        $this->configLayout = $node;
        $this->add($node);

        UXApplication::runLater(function () use ($node) {
            $this->size = [$node->width + 28 + 40, $node->height + 165];
            $this->minHeight = $this->height;

            $node->position = [20, 53];

            $node->rightAnchor = $node->leftAnchor = 20;
            $node->topAnchor = 63;
            $node->bottomAnchor = 60;
        });
    }

    /**
     * @return array
     */
    public function getData()
    {
        return GUI::getValues($this->configLayout->children, 'o_');
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        GUI::setValues($this->configLayout->children, $data, 'o_');
    }

    /**
     * @event show
     */
    public function doShow()
    {
        $this->showConfigCheckbox->selected = AbstractBuildType::isShowConfig();

        UXApplication::runLater(function () {
            $this->centerOnScreen();
        });
    }

    /**
     * @event saveButton.action
     */
    public function doSaveButtonClick()
    {
        $this->setResult(true);
        $this->hide();
    }

    /**
     * @event cancelButton.action
     */
    public function doCancelButtonClick()
    {
        $this->setResult(null);
        $this->hide();
    }
}