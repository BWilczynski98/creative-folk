<?php
declare(strict_types=1);
include "../../src/bootstrap.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$publication = [];

if (!$id) {
    redirect('publications.php', ['error' => 'Nie odnaleziono artykułu']);
}

if ($id) {
    $publication = $cms->publications()->getById($id, false);
}

if (!$publication) {
    redirect('publications.php', ['error' => 'Nie odnaleziono artykułu']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($publication['image_file'])) {
        $file_path = APP_ROOT . '/public/sent/' . $publication['image_file'];
        $cms->publications()->deleteImage($publication['id_image'], $file_path, $publication['id']);
    }

    $response = $cms->publications()->delete($publication['id']);
    $response ? redirect('publications.php', ['success' => 'Publikacja została poprawnie usunięta']) :
                redirect('publications.php', ['error' => 'Nie odnaleziono artykułu']);

}

$data = [
        'publication'   =>  $publication,
];

echo $twig->render('admin/delete-publication.html.twig', $data);
