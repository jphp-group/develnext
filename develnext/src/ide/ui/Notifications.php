<?php
namespace ide\ui;

use ide\Ide;
use ide\project\Project;
use php\gui\layout\UXAnchorPane;
use php\gui\UXAlert;
use php\gui\UXTextArea;
use php\gui\UXTrayNotification;
use php\lib\fs;

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

    static function attachException(UXTrayNotification $notify, \Exception $e) {
        $notify->on('click', function () use ($e) {
            $dialog = new UXAlert('ERROR');
            $dialog->title = 'Ошибка';
            $dialog->headerText = 'Произошла ошибка в DevelNext, сообщите об этом авторам';
            $dialog->contentText = $e->getMessage();
            $dialog->setButtonTypes(['Выход из DevelNext', 'Продолжить']);
            $pane = new UXAnchorPane();
            $pane->maxWidth = 100000;

            $class = get_class($e);

            $content = new UXTextArea("{$class}\n{$e->getMessage()}\n\nОшибка в файле '{$e->getFile()}'\n\t-> на строке {$e->getLine()}\n\n" . $e->getTraceAsString());
            $content->padding = 10;
            UXAnchorPane::setAnchor($content, 0);

            $pane->add($content);
            $dialog->expandableContent = $pane;
            $dialog->expanded = true;

            switch ($dialog->showAndWait()) {
                case 'Выход из DevelNext':
                    Ide::get()->shutdown();
                    break;
            }
        });
    }

    static function error($title, $message)
    {
        if ($message == "Validation") {
            return self::show($title, "Введите корректные данные", "ERROR");
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
        static::show('Добро пожаловать', 'Приветствуем тебя, ' . $data['login'] . ".");
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

    public static function errorWriteFile($file, \Exception $e = null)
    {
        $file = fs::name($file);

        if ($e) {
            $notify = static::error('Ошибка записи', "Файл '$file' недоступен для записи, нажмите сюда для подробностей");
            static::attachException($notify, $e);
        } else {
            static::error('Ошибка записи', "Файл '$file' недоступен для записи");
        }
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