<?php
declare(strict_types=1);
include "../src/bootstrap.php";
use PhpMysql\Email\Email;

$user_email = null;
$errors = null;
$send = null;

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $user_email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $errors = $user_email ? '' : 'Podaj swój adres e-mail';

    if (!$errors) {
        $id = $cms->users()->getUserIdByEmail($user_email);

        if ($id) {
            $token = $cms->tokens()->create($id, 'reset-password');

            $link = DOMAIN_NAME . 'new-password.php?token=' . $token;

            $from = $smtp_config['admin_email'];
            $address = $user_email;
            $subject = "Prośba o zresetowanie hasła";
            $message = 'Aby zresetować hasło, kliknij: <a href="' . $link . '">' . $link . '</a>';

            $email = new Email($smtp_config);
            $send = $email->send($from, $address, $subject, $message);
        }
    }
}

$data = [
    'navigation'    =>  $cms->categories()->getAll(),
    'errors'        =>  $errors,
    'send'          =>  $send,
];

echo $twig->render('reset-password.html.twig', $data);