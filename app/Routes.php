<?php
use \App\Core\Router;

Router::GET("/", 'Index@test', [
    'Auth'
]);
Router::GET("/{name}", 'Index@custom', [
    'Auth'
]);