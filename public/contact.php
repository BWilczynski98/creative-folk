<?php
declare(strict_types=1);
include "../src/bootstrap.php";

use PhpMysql\Validation\Validator;
use PhpMysql\Email\Email;

$from = '';
$message = '';
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $from = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $message = filter_input(INPUT_POST, 'message');
    $errors['email'] = $from ? '' : 'Nieprawidłowy adres e-mail';
    $errors['message'] = Validator::string($message, 1, 1000) ? '' : 'Proszę wprowadzić wiadomość do 1000 znaków';

    $invalid = implode($errors);

    if ($invalid) {
        $errors['warnings'] = 'Proszę poprawić błędy w formularzu';
    } else {
        $subject = "FORMULARZ KONTAKTOWY | Wiadomość od " . $from;
        $email = new Email($smtp_config);
        $res = $email->send($from, $smtp_config['admin_email'], $subject, $message);

        if ($res) {
            $success = 'Wiadomość została wysłana.';
            $from = '';
            $message = '';
        } else {
            echo $res;
        }
    }
}


$data = [
    'navigation'    => $cms->categories()->getAll(),
    'errors'        =>  $errors,
    'success'       =>  $success,
    'from'          =>  $from,
    'message'       =>  $message,
];


echo $twig->render('contact.html.twig', $data);