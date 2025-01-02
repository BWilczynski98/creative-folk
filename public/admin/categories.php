<?php
declare(strict_types=1);
include "../../src/bootstrap.php";

$success = filter_input(INPUT_GET, 'success');
$error = filter_input(INPUT_GET, 'error');

$categories = $cms->categories()->getAll();
?>

<?php include APP_ROOT . "/public/includes/admin-header.php"; ?>
<main class="container" id="content">
    <section class="header">
        <h1>Kategorie</h1>
        <?php if($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <p>
            <a href="category.php" class="btn btn-primary">Dodaj nową kategorię</a>
        </p>
    </section>
    <table class="admin">
        <tr>
            <th>Nazwa</th>
            <th class="edit">Zmień</th>
            <th class="del">Usuń</th>
        </tr>
        <?php foreach($categories as $category): ?>
        <tr>
            <td><strong><?= parse_to_html($category['name'])?></strong></td>
            <td><a href="category.php?id=<?= $category['id']?>" class="btn btn-primary">Zmień</a></td>
            <td><a href="delete-category.php?id=<?= $category['id']?>" class="btn btn-danger">Usuń</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
</main>
<?php include APP_ROOT . "/public/includes/admin-footer.php"; ?>
