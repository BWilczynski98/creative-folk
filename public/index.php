<?php
declare(strict_types=1);
include "../src/bootstrap.php";

$data['publications'] = $cms->publications()->getAll(true, null, null, 6);
$data['navigation'] = $cms->categories()->getAll();
$data['section'] = '';

echo $twig->render('index.html.twig', $data);
