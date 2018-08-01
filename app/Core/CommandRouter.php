<?php

namespace App\Core;

use App\Commands\AbstractCommand;

class CommandRouter
{

    protected $args;
    protected $commandClassName;
    protected $commandFunctionName;

    public function __construct(array $args)
    {
        $this->args = $args;
        $this->parseCommandArguments();
    }

    public function run()
    {
        /** @var AbstractCommand $command */
        $command = new $this->commandClassName($this->args);
        echo $command->run($this->commandFunctionName);
    }

    protected function parseCommandArguments()
    {
        if($this->args[0] == $_SERVER['SCRIPT_FILENAME']){
            array_shift($this->args);
        }
        list($this->commandClassName, $this->commandFunctionName) = explode(":", array_shift($this->args));
        $this->commandClassName = "App\\Commands\\".$this->commandClassName.'Command';
    }
}