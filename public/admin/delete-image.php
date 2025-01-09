<?php
declare(strict_types=1);
include "../../src/bootstrap.php";

is_admin($session['role']);

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

$data = [
        'id'            =>  $id,
        'publication'   =>  $publication,
];

echo $twig->render('admin/delete-image.html.twig', $data);
