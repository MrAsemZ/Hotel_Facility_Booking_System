<?php
// Loads classes/ClassName.php automatically when a class is first used
spl_autoload_register(function ($class) {
    $file = ROOT . '/classes/' . $class . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});
