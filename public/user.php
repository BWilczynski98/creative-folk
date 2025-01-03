<?php
require "../src/bootstrap.php";
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$data['navigation'] = $cms->categories()->getAll();
$data['user'] = $cms->users()->getById($id);
$data['publications'] = $cms->publications()->getAll(true, null, $id);


echo $twig->render('user.html.twig', $data);
