<?php
declare(strict_types=1);
include "../../src/bootstrap.php";

is_admin($session['role']);

$success = filter_input(INPUT_GET, 'success');
$error = filter_input(INPUT_GET, 'error');

$publications = $cms->publications()->getAll(false);

$data = [
        'success'       =>  $success,
        'error'         =>  $error,
        'publications'  =>  $publications,
];

echo $twig->render('admin/publications.html.twig', $data);
