<?php
declare(strict_types=1);
include "../src/bootstrap.php";

use PhpMysql\Validation\Validator;

$user = [
    'first_name'    =>  '',
    'last_name'     =>  '',
    'email'         =>  '',
    'password'      =>  '',
];

$errors = array_fill_keys(array_keys($user), '');
$errors['confirm_password'] = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user['first_name'] = filter_input(INPUT_POST, 'first_name');
    $user['last_name'] = filter_input(INPUT_POST, 'last_name');
    $user['email'] = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $user['password'] = filter_input(INPUT_POST, 'password');
    $confirm_password = filter_input(INPUT_POST, 'confirm_password');

    $errors['first_name'] = Validator::string($user['first_name'], 1, 30) ? '' : 'Imię musi się składać z 1-30 znaków';
    $errors['last_name'] = Validator::string($user['last_name'], 1, 60) ? '' : 'Nazwisko musi się skłądać z 1-60 znaków';
    $errors['email'] = $user['email'] ? '' : 'Adres email nieprawidłowy';
    $errors['password'] = Validator::password($user['password']) ? '' : 'Hasło musi się składać z co najmniej 8 znaków, w tym: <br>
                                                                         Małej litery<br>Dużej litery<br>Cyfry<br>Znaku specjalnego';
    $errors['confirm_password'] = ($user['password'] == $confirm_password) ? '' : 'Niezgodne hasła';

    $invalid = implode($errors);

    if (!$invalid) {
//        dd($user);
        $res = $cms->users()->create($user);
        if ($res) {
            redirect('login.php', ['success' => 'Dziękujemy za rejestrację! Zaloguj się']);
        } else {
            $errors['email'] = 'Istnieje konto o takim adresie email';
        }
    }
}

$data = [
    'user'      =>  $user,
    'errors'    =>  $errors,
];

echo $twig->render('registration.html.twig', $data);