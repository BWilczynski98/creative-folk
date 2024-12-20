<?php

define("APP_ROOT", dirname(__FILE__, 2)); // Ścieżka do katalogu projektu w moim przypadku: C:\xampp\htdocs\creative-folk
require APP_ROOT . '/src/functions.php';
require APP_ROOT . '/config/config.php';

if (DEV === false) {
    set_exception_handler('exceptionHandler');      // Obsługa wyjątków
    set_error_handler('errorHandler');              // Obsługa błędów
    register_shutdown_function('shutdownHandler');  // Procedura zamykająca
}

spl_autoload_register(function ($class) {
    $path = APP_ROOT . '/src/classes/';
    require $path . $class . '.php';
});

$cms = new CMS($dsn, $db_user, $db_password);
unset($dsn, $db_user, $db_password);