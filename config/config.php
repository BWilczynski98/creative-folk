<?php
define('DEV', true);

$directory_path = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']));
$parent_directory_path = dirname($directory_path);
define('DOCUMENT_ROOT', $parent_directory_path . '/public/');

// Podstawowa konfiguracja bazy danych
$db_type = "mysql";
$db_server = 'localhost';
$db_name = 'creative_folk';
$db_port = '3306';
$db_charset = 'utf8mb4';
$db_user = "testowy";
$db_password = "test123";

$dsn = "$db_type:host=$db_server;dbname=$db_name;port=$db_port;charset=$db_charset";

// Ustawienia przesyłania plików
define('UPLOADED_FILES_DIR', dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploaded' . DIRECTORY_SEPARATOR); // Przesyłanie obrazów
define('ALLOWED_MIME_TYPES', ['image/jpeg', 'image/png', 'image/gif']); // Dozwolone typy plików
define('ALLOWED_EXTENSIONS', ['jpeg', 'jpg', 'png', 'gif']); // Dozwolone rozszerzenia
define('MAX_FILE_SIZE', 5242880); // Maksymalny rozmiar pliku