<?php
declare(strict_types=1);
include "../src/bootstrap.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id) {
    $user = $cms->user()->getById($id);
} else {
    abort(404);
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    <title>User account</title>
</head>
<body class="flex w-full h-screen justify-center items-center">
<div class="bg-gray-300 w-1/2 px-8 py-10">
    <div class="prose">
        <h3>Profile information</h3>
        <p>Full name: <b class="tracking-wide"><?= htmlspecialchars("{$user['first_name']} {$user['last_name']}") ?></b>
        </p>
        <p>Email adres: <b class="tracking-wide"><?= htmlspecialchars($user['email']) ?></b></p>
        <p>Date join: <b
                    class="tracking-wide"><?= htmlspecialchars(date("Y-m-d", strtotime($user['created_at']))) ?></b></p>
    </div>
</div>

</body>
</html>
