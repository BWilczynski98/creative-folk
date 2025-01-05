<?php
declare(strict_types=1);
include "../../src/bootstrap.php";

$success = filter_input(INPUT_GET, 'success');
$error = filter_input(INPUT_GET, 'error');

$publications = $cms->publications()->getAll(false);

$data = [
        'success'       =>  $success,
        'error'         =>  $error,
        'publications'  =>  $publications,
];

echo $twig->render('admin/publications.html.twig', $data);
