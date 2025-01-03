<?php
declare(strict_types=1);
include "../src/bootstrap.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    abort(404);
}

$publication = $cms->publications()->getById($id);

if (!$publication) {
    abort(404);
}

$data['publication'] = $publication;
$data['navigation'] = $cms->categories()->getAll();

echo $twig->render('publication.html.twig', $data);
