<?php
declare(strict_types=1);
include "../src/bootstrap.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    abort(404);
}

$publication = $cms->publications()->getById($id);

if (!$publication) {
    abort(404);
}
?>
<?php include APP_ROOT . "/public/includes/header.php" ?>
<main class="article container" id="content">
    <section class="image">
        <img src="sent/<?= $publication['image_file'] ?? 'blank.png'?>"
             alt="<?= $publication['image_alt_text']?>">
    </section>
    <section class="text">
        <h1><?= $publication['title']?></h1>
        <div class="date"><?= formate_date($publication['created_at'])?></div>
        <div class="content"><?= $publication['content']?></div>
        <p class="credit">
            Zamieszczono w <a href="category.php?id=<?= $publication['id_category'] ?>"><?= $publication['category'] ?></a>
            przez: <a href="user.php?id=<?= $publication['id_user'] ?>"><?= $publication['author'] ?></a>
        </p>
    </section>
</main>
<?php include APP_ROOT . "/public/includes/footer.php" ?>
