<?php
declare(strict_types=1);
include "../../src/bootstrap.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$category = [];

// Walidacja id (Puste i nie w typie int)
if (!$id) {
    redirect('categories.php', ['error' => 'Nie odnaleziono kategorii']);
}

// Id nie jest puste i jest w typie int, próba pobrania kategorii
if ($id) {
    $category = $cms->categories()->getById($id);
}

// Nie odnaleziono kategorii z podanym id
if (!$category) {
    redirect('categories.php', ['error' => 'Nie odnaleziono kategorii']);
}

// Wywołanie dla przycisku "Potwierdź"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($category) {
        $response = $cms->categories()->delete($category['id']);
        redirect('categories.php', ['success' => 'Kategoria została poprawnie usunięta']);
    } else {
        redirect('categories.php', ['error' => 'Nie odnaleziono kategorii']);
    }
}

$data = [
        'category'  =>  $category
];

echo $twig->render('admin/delete-category.html.twig', $data);