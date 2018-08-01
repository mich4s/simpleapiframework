<?php

namespace App\Core;

class Config
{

    protected static $content = null;

    public function __construct()
    {
        $this->initConfiguration();
    }

    public function __get($name)
    {
        return isset(self::$content[$name]) ? self::$content[$name] : null;
    }

    protected function initConfiguration()
    {
        if(!self::$content)
            $this->loadConfigContent();
    }

    protected function loadConfigContent()
    {
        self::$content = json_decode(file_get_contents(__ROOTPATH__.'/config.json'));
    }

}
