<?php
include "../src/bootstrap.php";

$head_tags['title'] = "Stwórz nowy wpis";
$head_tags['description'] = "Formularz serwisu Creative Folk, który tworzy nowe wpisy";
$head_tags['keywords'] = "creative, folk, new-post, form, new-post-form";

$purifier = new HTMLPurifier();
$purifier->config->set("HTML.Allowed", "a[href],b,em");
$tekst = $purifier->purify("<em>This is xss attack</em>");
dd($tekst)
?>
<?php include APP_ROOT . "/public/includes/header.php"; ?>
<main>
    <form>
        <textarea></textarea>
        <button type="submit">Send</button>
    </form>
</main>
<?php include APP_ROOT . "/public/includes/footer.php"; ?>
