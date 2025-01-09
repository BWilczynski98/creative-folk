<?php
declare(strict_types=1);
include "../src/bootstrap.php";

use PhpMysql\Validation\Validator;

$success = filter_input(INPUT_GET, 'success') ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');

    $errors['email'] = $email ? '' : "Nieprawidłowy adres email";
    $errors['password'] = Validator::password($password) ? '' : 'Hasło musi się składać z co najmniej 8 znaków, w tym: <br>
                                                                         Małej litery<br>Dużej litery<br>Cyfry<br>Znaku specjalnego';

    $invalid = implode($errors);

    if (!$invalid) {
        $user = $cms->users()->login($email, $password);

        if ($user && $user['role'] == 'zawieszony') {
            $errors['warning'] = 'Konto zawieszone';
        } elseif ($user) {
            $cms->session()->create($user);
            redirect('user.php', ['id' => $user['id']]);
        } else {
            $errors['warning'] = "Proszę spróbować jeszcze raz";
        }

    } else {
        $errors['warning'] = "Proszę spróbować jeszcze raz";
    }
}

$data = [
    'errors' => [],
    'success' => $success,
    'navigation' => $cms->categories()->getAll(),
];

echo $twig->render('login.html.twig', $data);