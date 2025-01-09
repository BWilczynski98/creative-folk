<?php

function dd(...$vals): void
{
    foreach ($vals as $val) {
        echo "<pre>";
        var_dump($val);
        echo "</pre>";
    }
    die();
}

function abort($code = 404): void
{
    http_response_code($code);
    require APP_ROOT . "/public/404.php";
}

function parse_to_html(?string $text): string
{
    $text = $text ?? '';
    return htmlspecialchars($text, ENT_QUOTES|ENT_HTML5, 'UTF-8', true);
}

function formate_date(string $timestamp): string
{
    $date = date_create_from_format('Y-m-d H:i:s', $timestamp);
    return $date->format('d F Y');
}

/**
 ************* OBSŁUGA BŁĘDÓW NA PRODUKCJI *************
 */
function error_handler($type, $message = '',$file = '',$row = 0)
{
    throw new ErrorException($message, 0, $type, $file, $row);
}

function exception_handler($e)
{
    error_log($e);
    http_response_code(500);
    echo "<h1>Przepraszamy za usterki.</h1>
          <p>Administratorzy strony zostali poinformowani o problemie. Prosimy spróbować później.</p>";
}

function shutdown_handler()
{
    $blad = error_get_last();
    if ($blad) {
        $e = new ErrorException($blad['message'], 0, $blad['type'],
            $blad['file'], $blad['line']);
        exception_handler($e);
    }
}

function redirect(string $url, ?array $params = [], ?int $response_code = 302)
{
    $formatted_param = $params ? '?' . http_build_query($params) : '';
    $location = $url . $formatted_param;

    header('location: ' . $location, null, $response_code);
    exit();
}

function is_admin(string $role)
{
    if ($role !== 'admin') {
        header('Location: ' . DOCUMENT_ROOT);
        exit();
    }
}