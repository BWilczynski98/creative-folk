<?php
declare(strict_types=1);
include "../../src/bootstrap.php";

$success = filter_input(INPUT_GET, 'success');
$error = filter_input(INPUT_GET, 'error');

$categories = $cms->categories()->getAll();

$data = [
        'success'       =>  $success,
        'error'         =>  $error,
        'categories'    =>  $categories
];

echo $twig->render('admin/categories.html.twig', $data);