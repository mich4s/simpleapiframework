<?php

use \App\Core\Bootstrap;
require_once './vendor/autoload.php';
define("__ROOTPATH__", __DIR__);

(new Bootstrap($argv))->run();