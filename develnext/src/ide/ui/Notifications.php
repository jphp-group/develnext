<?php
namespace ide\ui;

use ide\Ide;
use ide\project\Project;
use php\gui\UXTrayNotification;

class Notifications
{
    static function show($title, $message, $type = 'NOTICE')
    {
        $notify = new UXTrayNotification($title, $message, $type);
        $notify->location = 'TOP_RIGHT';
        $notify->animationType = 'POPUP';
        $notify->verGap = 30;
        $notify->show();

        return $notify;
    }

    static function error($title, $message)
    {
        if ($message == "Validation") {
            return "Введите корректные данные"; // TODO improve this
        }

        return self::show($title, $message, 'ERROR');
    }

    static function warning($title, $message)
    {
        return self::show($title, $message, 'WARNING');
    }

    static function success($title, $message)
    {
        return self::show($title, $message, 'SUCCESS');
    }

    static function showAccountWelcome()
    {
        static::show('Приветствие', 'Добро пожаловать в социальную сеть DevelNext для разработчиков', 'INFORMATION');
    }

    static function showAccountUnavailable()
    {
        static::show('Аккаунт недоступен', 'Работа с аккаунтом временно недоступна, приносим свои извинения.', 'WARNING');
    }

    public static function showAccountAuthWelcome(array $data)
    {
        static::show('Добро пожаловать', 'Приветствуем тебя, ' . $data['name'] . ".");
    }

    public static function showException(\Exception $e)
    {
        return static::show('Произошла ошибка', $e->getMessage(), 'ERROR');
    }

    public static function showAccountAuthorizationExpired()
    {
        static::show('Данные входа устарели', 'Вам необходимо снова зайти под своим пользователем, т.к. данных предыдущего входа устарели.', 'WARNING');
    }

    public static function showExecuteUnableStop()
    {
        static::show('Проблемы с запуском', 'Мы не смогли корректно остановить программу, возможно она еще запущена.', 'WARNING');
    }

    public static function showInvalidValidation()
    {
        static::error('Ошибка валидации', 'Введите все необходимые данные корректно, не пропуская обязательные поля!');
    }

    public static function errorDeleteFile($file)
    {
        static::error('Ошибка удаления', "Файл '$file' невозможно удалить в данный момент, возможно он занят другой программой.");
    }

    public static function errorCopyFile($file)
    {
        static::error('Ошибка копирования', "Файл '$file' невозможно скопировать в данный момент, возможно недоступен файл или целевая папка.");
    }

    public static function warningFileOccurs($file)
    {
        $project = Ide::project();

        if ($project) {
            $file = $project->getAbsoluteFile($file);
            $file = $file->getRelativePath();
        }

        static::warning('Поврежденный файл', "$file поврежден, возможно некоторые данные утеряны.");
    }

    public static function showProjectIsDeleted()
    {
        Notifications::show('Проект удален', 'Ваш проект был успешно удален из общего доступа, при желании вы можете снова им поделиться.', 'SUCCESS');
    }

    public static function showProjectIsDeletedFail()
    {
        Notifications::error('Ошибка удаления', 'Мы не смогли удалить ваш проект, возможно сервис временно недоступен, попробуйте позже.');
    }
}