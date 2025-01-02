<?php
declare(strict_types=1);
include "../../src/bootstrap.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$publication = [];

if ($id) {
  $publication = $cms->publications()->getById($id, false);
  $publication_exist = boolval($publication);

  if (!$publication_exist) {
      redirect('publications.php', ['error' => 'Nie znaleziono publikacji 1']);
  }
} else {
    redirect('publications.php', ['error' => 'Nie znaleziono publikacji 2']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $res = $cms->publications()->updateImageAltText($publication['id_image'], $_POST['image_alt_text']);
      if ($res) {
          $location = 'Location: publication.php?id=' . $id;
          header($location);
          exit();
      }
}

?>

<?php include APP_ROOT . '/public/includes/admin-header.php'; ?>
<main class="container admin" id="content">
    <form action="edit-image-alt.php?id=<?= $id ?>" method="POST" class="narrow">
        <h1>Zmień tekst zastępczy dla tego zdjęcia</h1>
        <p>
            <img src="../sent/<?= $publication['image_file'] ?> ">
        </p>
        <label for="image_alt_text">Tekst zastępczy:</label>
        <input
            id="image_alt_text"
            name="image_alt_text"
            type="text"
            class="form-control"
            value="<?= $publication['image_alt_text'] ?>"
        ><br>
        <input type="submit" value="Aktualizuj" class="btn btn-primary" name="update">
        <a href="publication.php?id=<?= $id ?>" class="btn btn-danger">Anuluj</a>
    </form>
</main>
<?php include APP_ROOT . '/public/includes/admin-footer.php'; ?>