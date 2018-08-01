<?php

namespace App\Commands;

use App\Core\CommandResponse;

abstract class AbstractCommand
{

    private $inputParams = [];
    private $paramsDefinitions = [];

    public final function __construct(array $params)
    {
        $this->inputParams = $params;
        $this->define();
        $this->init();
    }

    public function run(string $actionName):CommandResponse
    {
        return new CommandResponse($this->$actionName());
    }

    protected final function defineParam(string $actionName, int $index, string $name)
    {
        if(!isset($this->paramsDefinitions[$actionName]))
            $this->paramsDefinitions[$actionName] = [];
        $this->paramsDefinitions[$actionName][$name] = isset($this->inputParams[$index]) ? $this->inputParams[$index] : null;
    }

    protected abstract function define();
    protected abstract function init();

}
