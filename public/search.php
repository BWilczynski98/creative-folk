<?php
include "../src/bootstrap.php";

// Domyślne head tagi
$head_tags['title'] = "Wyszukiwanie";
$head_tags['description'] = "Wyszukaj interesujący cię temat.";
$head_tags['keywords'] = "Creative Folk, creative, folk, search, wyszukiwarka";

// Przekazywane argumenty w URL
$term = filter_input(INPUT_GET, 'term'); // Szukany termin
$limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT) ?? 3; // Limit zwróconych wyników, domyślnie wartość 3
$offset = filter_input(INPUT_GET, 'offset', FILTER_VALIDATE_INT) ?? 0; // Przesunięcie zwróconych wyników, domyślnie wartość 0

$publications = []; // Inicjalizacja pustej tablicy dla znalezionych wyników
$results_count = 0; // Inicjalizacja liczby znalezionych wyników

// Warunek sprawdzający, czy została podana wartość szukana
if ($term) {
    $results_count = $cms->publications()->count($term); // Pobranie liczby elementów spełniających kryteria

    // Warunek sprawdzający, czy liczba elementów jest większa niż 0
    if ($results_count > 0) {
        $publications = $cms->publications()->search($term, $limit, $offset); // Pobranie wyników i przypisanie do tablicy

    }

    // Zmiana head tagów na odpowiednio opisujące wynik wyszukiwania
    $head_tags['title'] = "Wyniki wyszukiwania dla hasła " . parse_to_html($term);
    $head_tags['description'] = $head_tags['title'] . " w Creative Folk";
}

if ($results_count > $limit) {
    $total_pages = ceil($results_count / $limit);
    $current_page = ceil($offset / $limit) + 1;
}

?>

<?php include APP_ROOT . "/public/includes/header.php"; ?>
<main class="container" id="content">
    <!-- Pole do wpisania hasła szukanego   -->
    <section class="header">
        <form action="search.php" method="GET" class="form-search">
            <label for="search"><span>Wyszukaj: </span></label>
            <input
                    type="text"
                    name="term"
                    value="<?= parse_to_html($term) ?>"
                    id="search"
                    placeholder="Wpisz szukane słowo"
                    required
            />
            <input
                    type="submit"
                    value="Szukaj"
                    class="btn btn-search"
            />
        </form>
        <?php if ($term) : ?>
        <p>
            <b>Znaleziono</b>
            <?= $results_count ?> wyników
        </p>
        <?php endif ; ?>
    </section>

    <!-- Renderowanie znalezionych wyników   -->
    <section class="grid">
        <?php foreach($publications as $publication): ?>
        <article class="summary">
            <a href="publication.php?id=<?= $publication['id'] ?>">
                <img
                        src="sent/<?= parse_to_html($publication['image_file'] ?? 'blank.png') ?>"
                        alt="<?= parse_to_html($publication['image_alt_text']) ?>"
                />
                <h2><?= parse_to_html($publication['title']) ?></h2>
                <p><?= parse_to_html($publication['summary'])?></p>
            </a>
            <p class="credit">
                Zamieszczono w <a href="category.php?id=<?= $publication['id_category']?>"><?= $publication['category'] ?></a>
                przez: <a href="user.php?id=<?= $publication['id_user']?>"><?= $publication['author'] ?></a>
            </p>
        </article>
        <?php endforeach; ?>
    </section>

    <!-- Paginacja   -->
    <?php if ($results_count > $limit): ?>
    <nav class="pagination" role="navigation" aria-label="Navbar pagination">
        <ul>
            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <li>
                    <a href="?term=<?= $term ?>&limit=<?= $limit ?>&offset=<?= ($i - 1) * $limit?>" class="btn <?= $i == $current_page ? 'active" aria-current="true' : '' ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>
</main>
<?php include APP_ROOT . "/public/includes/footer.php"; ?>