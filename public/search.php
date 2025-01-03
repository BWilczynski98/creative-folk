<?php
include "../src/bootstrap.php";

$term = filter_input(INPUT_GET, 'term');
$limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT) ?? 3;
$offset = filter_input(INPUT_GET, 'offset', FILTER_VALIDATE_INT) ?? 0;

$publications = [];
$results_count = 0;
$total_pages = 0;
$current_page = 0;

if ($term) {
    $results_count = $cms->publications()->count($term);

    if ($results_count > 0) {
        $publications = $cms->publications()->search($term, $limit, $offset);
    }

    // Zmiana head tagów na odpowiednio opisujące wynik wyszukiwania
    $head_tags['title'] = "Wyniki wyszukiwania dla hasła " . parse_to_html($term);
    $head_tags['description'] = $head_tags['title'] . " w Creative Folk";
}

if ($results_count > $limit) {
    $total_pages = ceil($results_count / $limit);
    $current_page = ceil($offset / $limit) + 1;
}

$data['navigation'] = $cms->categories()->getAll();
$data['term'] = $term;
$data['results_count'] = $results_count;
$data['publications'] = $publications;
$data['limit'] = $limit;
$data['total_pages'] = $total_pages;
$data['current_page'] = $current_page;

echo $twig->render('search.html.twig', $data);
