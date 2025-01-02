<?php
require "../src/bootstrap.php";
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$user = $cms->users()->getById($id);
$publications = $cms->publications()->getAll(true, null, $id);

?>

<?php include APP_ROOT . "/public/includes/header.php"; ?>
    <main class="container" id="content">
        <section class="header">
            <h1><?= $user['first_name'] . " " . $user["last_name"]?></h1>
            <p class="uczestnik"><b>Uczestniczy od:</b> <?= formate_date($user['created_at']) ?></p>
            <img src="sent/<?= $user['profile_url'] ?? "uczestnik.png" ?>"
                 alt="Zdjęcie profilowe użytkownika <?= $user['first_name'] . ' ' . $user['last_name'] ?> "
                 class="profile"><br>
        </section>
        <section class="grid">

            <?php foreach($publications as $publication): ?>
                <article class="summary">
                    <a href="publication.php?id=<?= $publication['id'] ?>">
                        <img
                            src="sent/<?= htmlspecialchars($publication['image_file']) ?? 'blank.png' ?>"
                            alt="<?= htmlspecialchars($publication['image_alt_text']) ?>"
                        >
                        <h2><?= htmlspecialchars($publication['title']) ?></h2>
                        <p><?= htmlspecialchars($publication['summary'])?></p>
                    </a>
                    <p class="credit">
                        Zamieszczono w <a href="category.php?id=<?= $publication['id_category'] ?>"><?= $publication['category'] ?></a>
                        przez: <a href="user.php?id=<?= $publication['id_user'] ?>"><?= $publication['author'] ?></a>
                    </p>
                </article>
            <?php endforeach; ?>

        </section>
    </main>
<?php include APP_ROOT . "/public/includes/footer.php"; ?>