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
        static::show('Добро пожаловать в социальную сеть', 'Вы вошли в свой аккаунт под именем ' . $data['email']);
    }

    public static function showException(\Exception $e)
    {
        return static::show('Произошла ошибка', $e->getMessage(), 'ERROR');
    }
}