<?php
namespace ide\commands;

use ide\account\api\ServiceResponse;
use ide\editors\AbstractEditor;
use ide\forms\UpdateAvailableForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\ui\Notifications;
use php\gui\UXApplication;
use php\gui\UXDialog;

class CheckUpdatesCommand extends AbstractCommand
{
    public function getName()
    {
        return _('menu.help.check.updates');
    }

    public function getIcon()
    {
        return 'icons/update16.png';
    }

    public function withBeforeSeparator()
    {
        return true;
    }

    public function getCategory()
    {
        return 'help';
    }

    public function isAlways()
    {
        return true;
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        Ide::get()->getMainForm()->showPreloader(_('toast.search.updates'));

        Ide::service()->ide()->getLastUpdateAsync('NIGHT', function (ServiceResponse $response) {
            Ide::get()->getMainForm()->hidePreloader();

            $hash = Ide::get()->getConfig()->get('app.hash');

            if ($response->isSuccess()) {
                $rHash = $response->data()['hash'];

                if ($hash < $rHash) {
                    UXApplication::runLater(function () use ($response) {
                        $dialog = new UpdateAvailableForm();
                        $dialog->tryShow($response->data(), true);
                    });
                } else {
                    UXDialog::show(_('alert.last.version.installed'));
                }
            } else {
                Notifications::warning(_('account.server.unavailable'), _('account.update.service.unavailable.message'));
            }
        });
    }
}