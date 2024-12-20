<?php
declare(strict_types=1);
require "../src/bootstrap.php";

$res = $cms->category()->delete(29);

dd($res)
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create new category</title>
</head>
<body>

</body>
</html>
