<?php
spl_autoload_register(
    function($class){
        $file = __DIR__ ."\\$class.php";
        include_once $file;
    }
);