<?php
declare(strict_types=1);
include "../../src/bootstrap.php";
// TODO: Kiedy usuwa się publikacje to nie usuwa się zdjęcie z bazy danych
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$publication = [];

// Walidacja id (Puste i nie w typie int)
if (!$id) {
    redirect('publications.php', ['error' => 'Nie odnaleziono artykułu']);
}

// Id nie jest puste i jest w typie int, próba pobrania kategorii
if ($id) {
    $publication = $cms->publications()->getById($id, false);
}

// Nie odnaleziono kategorii z podanym id
if (!$publication) {
    redirect('publications.php', ['error' => 'Nie odnaleziono artykułu']);
}

// Wywołanie dla przycisku "Potwierdź"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($publication) {
        $response = $cms->publications()->delete($publication['id']);
        redirect('publications.php', ['success' => 'Publikacja została poprawnie usunięta']);
    } else {
        redirect('publications.php', ['error' => 'Nie odnaleziono artykułu']);
    }
}

$data = [
        'publication'   =>  $publication,
];

echo $twig->render('admin/delete-publication.html.twig', $data);
