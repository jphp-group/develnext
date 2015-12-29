<?php
namespace ide\account\ui;

use ide\forms\RegisterForm;
use ide\Ide;
use php\gui\framework\AbstractForm;
use php\gui\framework\EventBinder;
use php\gui\layout\UXAnchorPane;
use php\gui\UXLoader;
use php\gui\UXNode;
use php\io\Stream;

/**
 * Class NeedAuthPane
 * @package ide\account\ui
 */
class NeedAuthPane extends UXAnchorPane
{
    /**
     * NeedAuthPane constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $loader = new UXLoader();
        $ui = $loader->load(Stream::of(AbstractForm::DEFAULT_PATH . 'blocks/_NeedAuth.fxml'));

        UXAnchorPane::setAnchor($ui, 0);
        UXAnchorPane::setAnchor($this, 0);

        $this->add($ui);

        $binder = new EventBinder($ui, $this);
        $binder->setLookup(function (UXNode $context, $id) {
            return $this->lookup("#$id");
        });

        $binder->load();
    }

    /**
     * @event loginButton.action
     */
    public function doLogin()
    {
        Ide::accountManager()->authorize(true);
    }

    /**
     * @event registerLink.action
     */
    public function doRegister()
    {
        $registerForm = new RegisterForm();
        $registerForm->showAndWait();
    }
}