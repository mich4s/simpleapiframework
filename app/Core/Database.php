<?php

namespace App\Core;

class Database
{
    protected static $instance;
    protected $pdo;
    protected $config;

    public function __construct()
    {
        $this->config = (new Config())->database;
        $this->pdo = new \PDO();
    }
}
