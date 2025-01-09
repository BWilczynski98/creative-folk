<?php
declare(strict_types=1);
include "../../src/bootstrap.php";

is_admin($session['role']);

$success = filter_input(INPUT_GET, 'success');
$error = filter_input(INPUT_GET, 'error');

$categories = $cms->categories()->getAll();

$data = [
        'success'       =>  $success,
        'error'         =>  $error,
        'categories'    =>  $categories
];

echo $twig->render('admin/categories.html.twig', $data);