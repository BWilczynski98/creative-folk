<?php
declare(strict_types=1);
include "../../src/bootstrap.php";
use PhpMysql\Validation\Validator;

is_admin($session['role']);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$category = [
    'id'            => $id,
    'name'          => '',
    'description'   => '',
    'navigation'    => false,
];

$errors = [
    'warning'      => '',
    'name'          =>  '',
    'description'   =>  '',
];

if ($id) {
    $category = $cms->categories()->getById($id);

    if (!$category) {
        redirect("categories.php", ['error' => 'Kategoria nie odnaleziona']);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category['name'] = $_POST['name'];
    $category['description'] = $_POST['description'];
    $category['navigation'] = (isset($_POST['navigation']) && ($_POST['navigation'] == 1)) ? 1 : 0;

    $errors['name'] = Validator::string($category['name'], 1, 24) ? '' : 'Nazwa powinna składać się z 1-24 znaków.';
    $errors['description'] = Validator::string($category['description'], 1, 254) ? '' : 'Opis powinien składać się z 1-254 znaków.';
    $invalid = implode($errors);

    if ($invalid) {
        $errors['warning'] = 'Proszę poprawić błedy';
    } else {
        if ($id) {
            try {
                $res = $cms->categories()->update($category);
                redirect('categories.php', ['success' => 'Kategoria zapisana']);
            } catch (Exception $e) {
                $errors['warning'] = 'Nazwa kategorii już istnieje';
            }
        } else {
            unset($category['id']);
            $res = $cms->categories()->create($category);
            redirect('categories.php', ['success' => 'Kategoria zapisana']);
        }
    }

}

$data = [
        'category'  =>  $category,
        'errors'    =>  $errors,
];

echo $twig->render('admin/category.html.twig', $data);