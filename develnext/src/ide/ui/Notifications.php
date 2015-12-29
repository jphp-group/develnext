<?php
namespace ide\ui;

use php\gui\UXTrayNotification;

class Notifications
{
    static function show($title, $message, $type = 'NOTICE')
    {
        $notify = new UXTrayNotification($title, $message, $type);
        $notify->location = 'TOP_RIGHT';
        $notify->animationType = 'POPUP';
        $notify->show();

        return $notify;
    }

    static function error($title, $message)
    {
        return self::show($title, $message, 'ERROR');
    }

    static function warning($title, $message)
    {
        return self::show($title, $message, 'WARNING');
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
}