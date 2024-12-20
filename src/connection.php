<?php
// Typ bazy danych | Adres serwera | Nazwa bazy | Port bazy | Kodowanie bazy
// Dodatkowo użytkownik i jego hasło do bazy
// Opcje dla PDO

// Z czego ma się składać baza
// Tabele:
// Użytkownicy (id, imię, nazwisko, adres email, hasło, data utworzenia, zdjęcie profilowe
// Publikacja (id, tytuł, podsumowanie, treść, data utworzenia, id kategorii (klucz obcy), id uczestnika (klucz obcy), id obrazu (klucz obcy), data opublikowania
// Obrazy (id, plik, alt)
// Kategorie (id, nazwa, opis, nawigacja)

// Podstawowa konfiguracja
$type = "mysql";
$server = 'localhost';
$db_name = 'creative_folk';
$port = '3306';
$charset = 'utf8mb4';

$username = "testowy";
$password = "test123";

$option = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$dsn = "$type:host=$server;dbname=$db_name;port=$port;charset=$charset";
$pdo = new PDO($dsn, $username, $password, $option);

// Funkcja do handlowania z bazą
function pdoQuery(string $query, array $params = []): PDOStatement
{
    global $pdo;
    if (!empty($params)) {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
    } else {
        $stmt = $pdo->query($query);
    }

    return $stmt;
}