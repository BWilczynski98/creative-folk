<?php
declare(strict_types=1);
include "../../src/bootstrap.php";

is_admin($session['role']);

$data['number_of_categories'] = $cms->categories()->count();
$data['number_of_publications'] = $cms->publications()->count();

echo $twig->render('admin/index.html.twig', $data);
