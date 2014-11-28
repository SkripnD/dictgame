<?php

class Cache
{
    const ID_SETTINGS   = 'settings';
    const ID_TAGS       = 'tags';
    const ID_ADMIN_MENU = 'adminMenu';

    public static $titles = [
        self::ID_SETTINGS   => 'Основные настройки',
        self::ID_TAGS       => 'Теги',
        self::ID_ADMIN_MENU => 'Меню панели управления'
    ];
}
