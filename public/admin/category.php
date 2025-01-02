<?php
declare(strict_types=1);
include "../../src/bootstrap.php";
use Validation\Validator;

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
        header("location: categories.php");
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
        //todo: Walidacja przed duplikowaniem nazw kategorii
        // Problem rozwiązany, aczkolwiek do refaktoryzacji
        if ($id) {
            try {
                $res = $cms->categories()->update($category);
                header('location: categories.php');
            } catch (\PDOException $e) {
                $errors['warning'] = 'Nazwa kategorii już istnieje';
            }
        } else {
            unset($category['id']);
            $res = $cms->categories()->create($category);
            header('location: categories.php');
        }


    }

}

?>

<?php include APP_ROOT . "/public/includes/admin-header.php"; ?>
<main class="container admin" id="content">
    <form action="category.php?id=<?= $id?>" method="POST" class="narrow">
        <h1><?= $id ? 'Edytuj kategorie' : 'Dodaj kategorie'?></h1>

        <?php if ($errors['warning']): ?>
        <div class="alert alert-danger"><?= $errors['warning']?></div>
        <?php endif; ?>
        <div class="form-group">
            <label for="name">Nazwa:</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= parse_to_html($category['name']) ?>"
                class="form-control"

            />
            <span class="errors"><?= $errors['name']?></span>
        </div>

        <div class="form-group">
            <label for="description">Opis:</label>
            <textarea id="description" name="description" class="form-control" ><?= parse_to_html($category['description']) ?></textarea>
            <span class="errors"><?= $errors['description']?></span>
        </div>

        <div class="form-check">
            <input
                type="checkbox"
                id="navigation"
                name="navigation"
                value="1"
                class="form-check-input"
                <?= $category['navigation'] === 1 ? 'checked' : '' ?>
            />
            <label for="navigation" class="form-check-label">Nawigacja</label>

        </div>

        <input type="submit" id="save" value="Zapisz" class="btn btn-primary btn-save" />
    </form>
</main>
<?php include APP_ROOT . "/public/includes/admin-footer.php"; ?>
