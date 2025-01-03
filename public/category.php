<?php
declare(strict_types=1);
include "../src/bootstrap.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$category = $cms->categories()->getById($id);
$publications = $cms->publications()->getAll(true, $category['id'], null);

$data['category'] = $category;
$data['publications'] = $publications;
$data['navigation'] = $cms->categories()->getAll();

echo $twig->render('category.html.twig', $data);
