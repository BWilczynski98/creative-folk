<?php
declare(strict_types=1);
require "../src/bootstrap.php";
$new_category = [
    'id' => 13,
    'name' => "Europa wschodnia",
    'description' => "Poznaj uroki Europy wschodniej",
    'navigation' => 1,
];

$res = $cms->category()->update($new_category);

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
