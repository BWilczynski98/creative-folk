<?php

$title = $head_tags['title'] ?? "Creative Folk";
$description = $head_tags['description'] ?? "Oficjalna strona serwisu Creative Folk";
$keywords = $head_tags['keywords'] ?? "creative, folk";
?>

<!doctype html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="<?= htmlspecialchars($description) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($keywords) ?>">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">
    <link rel="shortcut icon" type="image/png" href="img/favicon.ico">
    <title><?= htmlspecialchars($title) ?></title>
</head>
<body>
    <header>
        <div class="container">
                <div>
                    <a class="skip-link" href="#content">Przejdź do treści</a>
                    <div class="logo">
                        <a href="index.php"><img src="img/logo.png" alt="Creative Folk"></a>
                    </div>
                </div>
                <nav>
                    <button id="toggle-navigation" aria-expanded="false">
                        <span class="icon-menu"></span><span class="hidden">Menu</span>
                    </button>
                    <ul id="menu">
                        <?php foreach($navbar_categories as $link): ?>
                            <?php if ($link['navigation'] === 1): ?>
                            <li><a href="category.php?id=<?= $link['id'] ?>">
                                    <?= $link['name'] ?>
                                </a></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <li>
                            <a href="search.php">
                                <span class="icon-search"></span><span class="search-text">Szukaj</span>
                            </a>
                        </li>
                    </ul>
                </nav>

        </div>
    </header>

