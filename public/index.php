<?php
declare(strict_types=1);
include "../src/bootstrap.php";

$publications = $cms->publications()->getAll(true, null, null, 6);
//dd($publications);
$section = '';
$head_tags['title'] = "Strona główna - Creative Folk";
$head_tags['description'] = "Opis strony głównej serwisu Creative Folk";
$head_tags['keywords'] = "creative, folk, main page";

?>
<?php include APP_ROOT . "/public/includes/header.php"; ?>
<main class="container grid" id="container">
    <?php foreach($publications as $publication): ?>
        <article class="summary">
            <a href="publication.php?id=<?= $publication['id'] ?>">
                <img
                        src="sent/<?= parse_to_html($publication['image_file'] ?? "blank.png") ?>"
                        alt="<?= parse_to_html($publication['image_alt_text'] ?? 'Zdjęcie zastępcze') ?>"
                >
                <h2><?= parse_to_html($publication['title']) ?></h2>
                <p><?= parse_to_html($publication['summary'])?></p>
            </a>
            <p class="credit">
                Zamieszczono w <a href="category.php?id=<?= $publication['id_category'] ?>"><?= $publication['category'] ?></a>
                przez: <a href="user.php?id=<?= $publication['id_user'] ?>"><?= $publication['author'] ?></a>
            </p>
        </article>
    <?php endforeach; ?>
</main>
<?php include APP_ROOT . "/public/includes/footer.php" ?>
