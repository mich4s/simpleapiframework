<?php

namespace App\Core;

class CommandResponse
{

    private $response;
    private $parsedResponse;

    public function __construct($response)
    {
        $this->response = $response;
        $this->handleResponseType();
    }

    public function __toString():string
    {
        return $this->parsedResponse;
    }

    private function handleResponseType()
    {
        $methodName = 'handle'.ucfirst(gettype($this->response));
        if (method_exists($this, $methodName)) {
            $this->$methodName();
        } else {
           $this->parsedResponse = json_encode($this->response);
        }
    }

    private function handleArray()
    {
        $this->parsedResponse = '';
        foreach ($this->response as $name => $value) {
            $this->parsedResponse .= "\n".$name.":\n".$value;
        }
    }

}
