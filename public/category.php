<?php
declare(strict_types=1);
include "../src/bootstrap.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$category = $cms->categories()->getById($id);

$head_tags['title'] = $category['name'];
$head_tags['description'] = $category['description'];

$publications = $cms->publications()->getAll(true, $category['id'], null);

?>
<?php include APP_ROOT . "/public/includes/header.php"; ?>
<main class="container" id="content">
    <section class="header">
        <h1><?= parse_to_html($category['name']) ?></h1>
        <p><?= parse_to_html($category['description']) ?></p>
    </section>
    <section class="grid">
    <?php foreach($publications as $publication): ?>
        <article class="summary">
            <a href="publication.php?id=<?= $publication['id'] ?>">
                <img
                    src="sent/<?= parse_to_html($publication['image_file'] ?? "blank.png") ?>"
                    alt="<?= $publication['image_alt_text'] ?>">
                <h1><?= $publication['image_file'] ?></h1>
                <h2><?= parse_to_html($publication['title']) ?></h2>
                <p><?= parse_to_html($publication['summary'])?></p>
            </a>
            <p class="credit">
                Zamieszczono w <a href="category.php?id=<?= $publication['id_category'] ?>"><?= $publication['category'] ?></a>
                przez: <a href="user.php?id=<?= $publication['id_user'] ?>"><?= $publication['author'] ?></a>
            </p>
        </article>
    <?php endforeach; ?>
    </section>
</main>


