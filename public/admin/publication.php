<?php
declare(strict_types=1);
include "../../src/bootstrap.php";
use PhpMysql\Validation\Validator;

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$publication = [
        'id'                =>  $id,
        'title'             =>  '',
        'summary'           =>  '',
        'content'           =>  '',
        'id_user'           =>  0,
        'id_category'       =>  0,
        'id_image'          =>  null,
        'published'         =>  false,
        'image_file'        =>  '',
        'image_alt_text'    =>  '',
];
$errors = [
        'warning'           =>  '',
        'title'             =>  '',
        'summary'           =>  '',
        'content'           =>  '',
        'author'            =>  '',
        'category'          =>  '',
        'image_file'        =>  '',
        'image_alt_text'    =>  '',
];

if ($id) {
    $publication = $cms->publications()->getById($id, false);
    $publication_exist = boolval($publication);

    if (!$publication_exist) {
        redirect('publications.php', ['error' => 'Publikacja nie odnaleziona']);
    }
}

$authors = $cms->users()->getAll();
$categories = $cms->categories()->getAll();

$identifiers = [
        'authors'       =>  array_map(function ($author) {
            return $author['id'];
        }, $authors),
        'categories'    =>  array_map(function ($category) {
            return $category['id'];
        }, $categories)
];

// Onsubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Zmienne pomocnicze do pracy z przesyłanym zdjęciem
    $image_tmp = $_FILES['image']['tmp_name'] ?? null;
    $image_name = $_FILES['image']['name'] ?? null;
    $image_error = $_FILES['image']['error'] ?? null;
    $image_size = $_FILES['image']['size'] ?? null;
    $image_extension = $image_name ? pathinfo($image_name, PATHINFO_EXTENSION) : null;
    $image_save_path = '';

    // Sprawdzenie, czy plik nie przekracza ograniczeń dotyczących rozmiaru
    $errors['image_file'] = ($image_tmp && $image_error === 1) ? 'Plik jest za duży' : '';

    // Sprawdzenie, czy zdjęcie zostało prawidłowo przesłane
    if ($image_tmp && $image_error == 0) {
        $publication['image_alt_text'] = $_POST['image_alt_text'];

        $errors['image_file'] = in_array(mime_content_type($image_tmp), ALLOWED_MIME_TYPES) ? '' : 'Nieobsługiwany typ pliku. ';
        $errors['image_file'] .= in_array(strtolower($image_extension), ALLOWED_EXTENSIONS) ? '' : 'Nieobsługiwane rozszerzenie. ';
        $errors['image_file'] .= ($image_size <= MAX_FILE_SIZE) ? '' : 'Plik jest za duży. ';
        $errors['image_file'] = (Validator::string($publication['image_alt_text'], 1, 254)) ? '' : 'Tekst zastępczy musi mieć 1-254 znaków.';

        // Jeśli plik z obrazem jest prawidłowy, określ miejsce zapisu
        if (empty($errors['image_file']) && empty($errors['image_alt_text'])) {
            $publication['image_file'] = file_path_generator($image_name, UPLOADED_FILES_DIR);
            $image_save_path = UPLOADED_FILES_DIR . $publication['image_file'];
        }

    }

    // Początkowa konfiguracja Purifer
    $purifier = new HTMLPurifier();
    $purifier->config->set('HTML.Allowed', 'p,br,strong,em,a[href],img[src|alt]');

    // Pobieranie danych publikacji
    $publication['title']       =   $_POST['title'];
    $publication['summary']     =   $_POST['summary'];
    $publication['content']     =   $purifier->purify($_POST['content']); // Wyczyszczenie treści z niedozwolonych znaczników
    $publication['id_user']     =   $_POST['id_user'];
    $publication['id_category'] =   $_POST['id_category'];
    $publication['published']   =   (isset($_POST['published']) && ($_POST['published'] == 1) ? 1 : 0);

    // Walidowanie pól publikacji
    $errors['title']    =   Validator::string($publication['title'], 1, 80) ? '' : 'Tytuł musi mieć 1-80 znaków';
    $errors['summary']  =   Validator::string($publication['summary'], 1, 254) ? '' : 'Podsumowanie musi mieć 1-254 znaków';
    $errors['content']  =   Validator::string($publication['content'], 1, 100000) ? '' : 'Treść musi mieć 1-100 000 znaków';
    $errors['author']   =   in_array($publication['id_user'], $identifiers['authors']) ? '' : 'Proszę wybrać autora';
    $errors['category'] =   in_array($publication['id_category'], $identifiers['categories']) ? '' : 'Proszę wybrać kategorie';

    $invalid = implode($errors);

    if ($invalid) {
        $errors['warning'] = 'Prosimy o poprawienie błędów';
    } else {
        $result = null;

        if ($id) {
            $result = $cms->publications()->update($publication, ['tmp_name' => $image_tmp, 'path' => $image_save_path]);
        } else {
            unset($publication['id']);
            $result = $cms->publications()->create($publication, ['tmp_name' => $image_tmp, 'path' => $image_save_path]);
        }

        if ($result) {
            redirect('publications.php', ['success' => 'Publikacja zapisana']);
        } else {
            $errors['warning'] = 'Istnieje już publikacja o takim tytule';
        }
    }

    $publication['image_file'] = boolval($publication['image_file']) ? $publication['image_file'] : '';
}

function file_path_generator(string $file, string $sys_directory_path): string
{
    $name = pathinfo($file, PATHINFO_FILENAME);
    $ext = pathinfo($file, PATHINFO_EXTENSION);

    $cleared_name = preg_replace("/[^A-z0-9]/", "-", $name);
    $file_path = $cleared_name . '.' . $ext;

    $i = 0;
    while (file_exists($sys_directory_path . $file_path)) {
        $i = $i + 1;
        $file_path = $name . $i . '.' . $ext;
    }

    return $file_path;
}

$data['publication'] = $publication;
$data['categories'] = $categories;
$data['authors'] = $authors;
$data['errors'] = $errors;

echo $twig->render('admin/publication.html.twig', $data);
