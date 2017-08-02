<?php
namespace ide\forms;

use ide\Ide;
use ide\Logger;
use php\gui\framework\AbstractForm;
use php\gui\framework\DataUtils;
use php\gui\UXForm;
use php\gui\UXLabeled;
use php\gui\UXMenu;
use php\gui\UXMenuBar;
use php\gui\UXMenuItem;
use php\gui\UXNode;
use php\gui\UXTextInputControl;
use php\lib\str;
use php\util\Regex;

/**
 * Class AbstractIdeForm
 * @package ide\forms
 *
 * @property Ide $_app
 */
class AbstractIdeForm extends AbstractForm
{
    public function __construct(UXForm $origin = null)
    {
        parent::__construct($origin);

        if (Ide::isCreated()) {
            $this->owner = Ide::get()->getMainForm();
        }

        Logger::info("Create form " . get_class($this));

        $this->on('show', function () {
            $formName = get_class($this);

            Logger::info("Show form '$formName' ..");

            Ide::get()->trigger('showForm', [$this]);
        }, __CLASS__);

        $this->on('hide', function () {
            $formName = get_class($this);

            Logger::info("Hide form '$formName' ..");

            Ide::get()->trigger('hideForm', [$this]);
        }, __CLASS__);

        $this->_app->on('setThemeStyle', function ($oldRes, $newRes) {
            $this->resetIdeThemeStyle($oldRes);
            $this->setIdeThemeStyle($newRes);
        });

        $this->setIdeThemeStyle($this->_app->getThemeStyle());
    }

    protected function resetIdeThemeStyle($oldResource)
    {
        if ($oldResource) {
            $this->removeStylesheet($oldResource);
        }
    }

    protected function setIdeThemeStyle($newResource)
    {
        if ($newResource) {
            $this->addStylesheet($newResource);
        }
    }

    protected function init()
    {
        parent::init();

        $l10n = Ide::get()->getL10n();

        $this->title = $l10n->translate($this->title);
        $l10n->translateNode($this->layout);
    }

}