<?php
namespace ide\protocol\handlers;

use ide\account\api\ServiceResponse;
use ide\commands\AbstractProjectCommand;
use ide\forms\MessageBoxForm;
use ide\forms\SharedProjectDetailForm;
use ide\Ide;
use ide\Logger;
use ide\protocol\AbstractProtocolHandler;
use ide\ui\Notifications;
use php\lib\str;

/**
 * Class OpenProjectProtocolHandler
 * @package ide\protocol\handlers
 */
class OpenProjectProtocolHandler extends AbstractProtocolHandler
{
    /**
     * @param string $query
     * @return bool
     */
    public function isValid($query)
    {
        return str::startsWith($query, 'project:');
    }

    /**
     * @param $query
     * @return bool
     */
    public function handle($query)
    {
        $uid = str::sub($query, str::length('project:'));

        if (str::endsWith($uid, '/')) {
            $uid = str::sub($uid, 0, str::length($uid) - 1);
        }

        uiLater(function () use ($uid) {
            Ide::service()->projectArchive()->getAsync($uid, function (ServiceResponse $response) use ($uid) {
                if ($response->isSuccess()) {
                    uiLater(function () use ($response) {
                        Notifications::show('Обнаружен проект', 'Мы обнаружили ссылку на общедоступный проект, вы можете его открыть.', 'INFORMATION');
                        $dialog = new SharedProjectDetailForm($response->data()['uid']);
                        $dialog->showAndWait();
                    });
                } else {
                    Logger::error("Unable to get project, uid = $uid, {$response->toLog()}");
                    Notifications::error('Ошибка открытия', 'Ссылка на проект некорректная или он был уже удален.');
                }
            });
        });
    }
}