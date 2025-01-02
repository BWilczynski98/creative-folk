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

?>

<?php include APP_ROOT . "/public/includes/admin-header.php"; ?>
<main class="container admin" id="content">
    <form class="narrow" action="delete-category.php?id=<?= $category['id']?>" method="POST">
        <h1>Usuń kategorię</h1>
        <p>Kliknij <em>Potwierdź</em>, aby usunąć kategorię: <em><?= parse_to_html($category['name'] ?? 'Nazwa kategorii') ?></em></p>
        <input class="btn btn-primary" type="submit" name="delete" value="Potwierdź">
        <a href="categories.php" class="btn btn-danger">Anuluj</a>
    </form>
</main>
<?php include APP_ROOT . "/public/includes/admin-footer.php"; ?>