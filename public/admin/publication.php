<?php
declare(strict_types=1);
include "../../src/bootstrap.php";
use Validation\Validator;

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

    // Pobieranie danych publikacji
    $publication['title']       =   $_POST['title'];
    $publication['summary']     =   $_POST['summary'];
    $publication['content']     =   $_POST['content'];
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

?>

<?php include APP_ROOT . "/public/includes/admin-header.php"; ?>
<form action="publication.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
    <main class="container admin" id="content">
        <h1><?= $id ? 'Edytuj publikacje' : 'Dodaj publikacje' ?></h1>
        <?php if($errors['warning']): ?>
            <div class="alert alert-danger"><?= $errors['warning'] ?></div>
        <?php endif; ?>
        <div class="admin-article">
            <section class="image">
            <?php if($publication['image_file']): ?>
                <label>Obraz:</label>
                <img src="../sent/<?= parse_to_html($publication['image_file']) ?>" alt="<?= parse_to_html($publication['image_alt_text']) ?>">
                <p class="alt"><strong>Tekst zastępczy (alt): </strong><?= parse_to_html($publication['image_alt_text']) ?></p>
                <a href="edit-image-alt.php?id=<?= $id ?>" class="btn btn-secondary">Edytuj tekst zastępczy</a>
                <a href="delete-image.php?id=<?= $id ?>" class="btn btn-secondary">Usuń obraz</a>
            <?php else: ?>
                <label for="image">Prześlij obraz:</label>
                <div class="form-group image-placeholder">
                    <input
                            type="file"
                            accept="image/png, image/jpeg"
                            name="image"
                            class="form-control-file"
                            id="image"
                    ><br>
                    <span class="errors"><?= $errors['image_file'] ?></span>
                </div>
                <div class="form-group">
                    <label for="image_alt_text">Tekst zastępczy (alt): </label>
                    <input id="image_alt_text" name="image_alt_text" class="form-control" type="text">
                    <span class="errors"><? errors['image_alt_text'] ?></span>
                </div>
            <?php endif; ?>
            </section>

            <section class="text">
                <div class="form-group">
                    <label for="title">Tytuł: </label>
                    <input
                            type="text"
                            id="title"
                            name="title"
                            class="form-control"
                            value="<?= parse_to_html($publication['title']) ?>"
                    >
                    <span class="errors"><?= $errors['title']?></span>
                </div>
                <div class="form-group">
                    <label for="summary">Podsumowanie: </label>
                    <textarea name="summary" id="summary" class="form-control"><?= parse_to_html($publication['summary']) ?></textarea>
                    <span class="errors"><?= $errors['summary'] ?></span>
                </div>
                <div class="form-group">
                    <label for="content">Treść: </label>
                    <textarea name="content" id="content" class="form-control"><?= $publication['content']?></textarea>
                    <span class="errors"><?= $errors['content'] ?></span>
                </div>
                <div class="form-group">
                    <label for="id_user">Autor: </label>
                    <select name="id_user" id="id_user">
                        <?php foreach($authors as $author): ?>
                            <option
                                    value="<?= $author['id'] ?>"
                                    <?= ($publication['id_user'] == $author['id']) ? 'selected' : ''; ?>
                            >
                                <?= parse_to_html($author['first_name'] . ' ' . $author['last_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="error"><?= $errors['author'] ?></span>
                </div>
                <div class="form-group">
                    <label for="id_category">Kategoria: </label>
                    <select name="id_category" id="id_category">
                        <?php foreach($categories as $category): ?>
                            <option
                                value="<?= $category['id'] ?>"
                                <?= ($publication['id_category'] == $category['id']) ? 'selected' : ''; ?>
                            >
                                <?= parse_to_html($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="error"><?= $errors['category'] ?></span>
                </div>
                <div class="form-check">
                    <input
                            type="checkbox"
                            id="published"
                            name="published"
                            value="1"
                            class="form-check-input"
                            <?= ($publication['published'] == 1) ? 'checked' : '' ?>
                    >
                    <label class="form-check-label" for="published">Opublikowano</label>
                </div>
                <input type="submit" name="update" value="Zapisz" class="btn btn-primary">
            </section>
        </div>
    </main>
</form>
<?php include APP_ROOT . "/public/includes/admin-footer.php"; ?>