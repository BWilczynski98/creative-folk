<?php
declare(strict_types=1);
include "../../src/bootstrap.php";

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
?>

<?php include APP_ROOT . "/public/includes/admin-header.php"; ?>
<main class="container admin" id="content">
    <form class="narrow" action="delete-publication.php?id=<?= $publication['id']?>" method="POST">
        <h1>Usuń publikacje</h1>
        <p>Kliknij <em>Potwierdź</em>, aby usunąć kategorię: <em><?= parse_to_html($publication['title'] ?? 'Nazwa artykułu') ?></em></p>
        <input type="submit" class="btn btn-primary" value="Potwierdź" name="delete">
        <a href="publications.php" class="btn btn-danger">Anuluj</a>
    </form>
</main>
<?php include APP_ROOT . "/public/includes/admin-footer.php"; ?>
