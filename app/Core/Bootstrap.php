<?php

namespace App\Core;

use Symfony\Component\HttpFoundation\Request;

class Bootstrap
{
    /**
     * @var Request
     */
    protected $request;
    protected $args;

    public function __construct(array $argv = null)
    {
        $this->args = $argv;
    }

    public function run()
    {
        switch(php_sapi_name())
        {
            case 'cli':
                $this->runCommand();
                break;
            default:
                $this->init();
                break;
        }
    }

    protected function init()
    {
        try{
            $this->request = new Request($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);
            (new Router($this->request))->dispatch()->send();
        } catch(\Exception $e) {
            var_dump($e);
        }
    }

    protected function runCommand()
    {
        (new CommandRouter($this->args))->run();
    }
}