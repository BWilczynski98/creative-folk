<?php

use CMS\CMS;

define("APP_ROOT", dirname(__FILE__, 2)); // Ścieżka do katalogu projektu w moim przypadku: C:\xampp\htdocs\creative-folk
require APP_ROOT . '/src/functions.php';
require APP_ROOT . '/config/config.php';
require APP_ROOT . "/src/metatags.php";
require APP_ROOT . "/vendor/autoload.php"; // Zastępuje funkcje spl_autoload_register

if (DEV === false) {
    set_exception_handler('exception_handler');      // Obsługa wyjątków
    set_error_handler('error_handler');              // Obsługa błędów
    register_shutdown_function('shutdown_handler');  // Procedura zamykająca
}

spl_autoload_register(function ($class) {
    $path = APP_ROOT . '/src/classes/';
    require $path . $class . '.php';
});

$cms = new CMS($dsn, $db_user, $db_password);
unset($dsn, $db_user, $db_password);

$navbar_categories = $cms->categories()->getAll();