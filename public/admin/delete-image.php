<?php
declare(strict_types=1);
include "../../src/bootstrap.php";
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$publication = [];

if ($id) {
    $publication = $cms->publications()->getById($id, false);
    $publication_exist = boolval($publication);

    if (!$publication_exist) {
        redirect('publications.php', ['error' => 'Nie znaleziono publikacji']);
    }
} else {
    redirect('publications.php', ['error' => 'Nie znaleziono publikacji']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file_path = APP_ROOT . '/public/sent/' . $publication['image_file'];
    if (file_exists($file_path)) {
        $cms->publications()->deleteImage($publication['id_image'], $file_path, $id);
        $location = 'Location: publication.php?id=' . $id;
        header($location);
        exit();
    }
}

?>

<?php include APP_ROOT . "/public/includes/admin-header.php" ?>
<main class="container admin" id="content">
    <form action="delete-image.php?id=<?= $id ?>" method="POST" class="narrow">
        <h1>Usuń obraz</h1>
        <p><img
                src="../sent/<?= parse_to_html($publication['image_file']) ?>"
                al="<?= parse_to_html($publication['image_alt_text']) ?>"
            >
        </p>
        <p>Kliknij <em>Potwierdź</em>, aby usunąć obraz:</p>
        <input type="submit" class="btn btn-primary" name="delete" value="Potwierdź">
        <a href="publication.php?id=<?= $id ?>" class="btn btn-danger">Anuluj</a>
    </form>
</main>
