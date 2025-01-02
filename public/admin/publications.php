<?php
declare(strict_types=1);
include "../../src/bootstrap.php";

$success = filter_input(INPUT_GET, 'success');
$error = filter_input(INPUT_GET, 'error');

$publications = $cms->publications()->getAll(false);
?>

<?php include APP_ROOT . "/public/includes/admin-header.php"; ?>
<main class="container" id="content">
    <section class="header">
        <h1>Publikacje</h1>
        <?php if($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <p>
            <a href="publication.php" class="btn btn-primary">Dodaj nową publikację</a>
        </p>
    </section>
    <table>
        <tr>
            <th>Obraz</th>
            <th>Tytuł</th>
            <th class="created">Utworzono</th>
            <th class="pub">Widoczna</th>
            <th class="edit">Zmień</th>
            <th class="del">Usuń</th>
        </tr>
        <?php foreach($publications as $publication): ?>
        <tr>
            <td>
                <img
                    src="../sent/<?= parse_to_html($publication['image_file'] ?? 'blank.png')  ?>"
                    alt="<?= parse_to_html($publication['image_alt_text']) ?>"
                >
            </td>
            <td>
                <strong><?= parse_to_html($publication['title']) ?></strong>
            </td>
            <td>
                <?= formate_date($publication['created_at']) ?>
            </td>
            <td>
                <?= $publication['published'] ? 'Tak' : 'Nie' ?>
            </td>
            <td>
                <a href="publication.php?id=<?= $publication['id']?>" class="btn btn-primary">Zmień</a>
            </td>
            <td>
                <a href="delete-publication.php?id=<?= $publication['id']?>" class="btn btn-danger">Usuń</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</main>
<?php include APP_ROOT . "/public/includes/admin-footer.php"; ?>
