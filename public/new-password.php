<?php
declare(strict_types=1);
include "../src/bootstrap.php";

use PhpMysql\Validation\Validator;
use PhpMysql\Email\Email;

$errors = [];

$token = filter_input(INPUT_GET, 'token');
if (!$token) {
    redirect("login.php");
}

$user_id = $cms->tokens()->validate($token, 'reset-password');
if ($user_id === 0) {
    redirect('login.php', ['error' => 'Link stracił ważność']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = filter_input(INPUT_POST, 'password');
    $confirm_password = filter_input(INPUT_POST, 'confirm_password');

    $errors['password'] = Validator::password($password) ? '' : 'Hasło musi się składać z co najmniej 8 znaków, w tym: <br>
                                                                         Małej litery<br>Dużej litery<br>Cyfry<br>Znaku specjalnego';
    $errors['confirm_password'] = $password === $confirm_password ? '' : 'Niezgodne hasła';
    $invalid = implode($errors);

    if ($invalid) {
        $errors['warning'] = 'Proszę wprowadzić prawidłowe haslo';
    } else {
        $password_update_success = $cms->users()->updatePassword($user_id, $password);

        if ($password_update_success) {
            $user_data = $cms->users()->getById($user_id);

            $subject = 'Hasło zostało zaktualizowane';
            $message = 'Twoje hasło zostało zmienione dnia ' . date('Y-m-d H:i:s') . ' - jeśli nie resetowałeś hasła, wyślij email na adres ' . $smtp_config['admin_email'];

            $email = new Email($smtp_config);
            $email->send($smtp_config['admin_email'], $user_data['email'], $subject, $message);

            redirect('login.php', ['success' => 'Hasło zostało zmienione']);
        }
    }

}


$data = [
    'navigation'    =>  $cms->categories()->getAll(),
    'token'         =>  $token,
    'errors'        =>  $errors,
];

echo $twig->render('new-password.html.twig', $data);