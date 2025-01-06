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

$data = [
    'id'            =>  $id,
    'publication'   =>  $publication,
];

echo $twig->render('admin/edit-image-alt.html.twig', $data);