<?php

namespace App\Core;

class CommandResponse
{

    private $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    public function __toString():string
    {
        return json_encode($this->response);
    }

}
