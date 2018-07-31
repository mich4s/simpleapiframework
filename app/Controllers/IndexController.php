<?php

namespace App\Controllers;

class IndexController extends AbstractController
{

    public function custom($name)
    {
        return ["test" => $name];
    }

}