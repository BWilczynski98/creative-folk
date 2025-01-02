<?php
declare(strict_types=1);
include "../../src/bootstrap.php";

$number_of_categories = $cms->categories()->count();
$number_of_publications = $cms->publications()->count();
?>

<?php include APP_ROOT . "/public/includes/admin-header.php"; ?>
<main class="container" id="content">
    <section class="header">
        <h1>Admin</h1>
    </section>
    <table class="admin">
        <tr>
            <th></th>
            <th>Liczba</th>
            <th class="create">Utwórz</th>
            <th class="view">Pokaż</th>
        </tr>
        <tr>
            <td><strong>Kategorie</strong></td>
            <td><?= $number_of_categories ?></td>
            <td><a href="category.php?id=" class="btn btn-primary">Dodaj</a></td>
            <td><a href="categories.php" class="btn btn-primary">Pokaż</a></td>
        </tr>
        <tr>
            <td><strong>Publikacje</strong></td>
            <td><?= $number_of_publications ?></td>
            <td><a href="publication.php" class="btn btn-primary">Dodaj</a></td>
            <td><a href="publications.php" class="btn btn-primary">Pokaż</a></td>
        </tr>
    </table>
</main>
<?php include APP_ROOT . "/public/includes/admin-footer.php" ?>