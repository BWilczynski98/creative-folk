<?php

namespace PhpMysql\Email;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
class Email
{
    protected ?PHPMailer $phpmailer = null;

    public function __construct(array $smtp_config)
    {
        $this->phpmailer = new PHPMailer(true);
        $this->phpmailer->SMTPAuth = true;
        $this->phpmailer->isSMTP();
        $this->phpmailer->Host = $smtp_config['server'];
        $this->phpmailer->Username = $smtp_config['username'];
        $this->phpmailer->Password = $smtp_config['password'];
        $this->phpmailer->SMTPSecure = $smtp_config['security'];
        $this->phpmailer->Port = $smtp_config['port'];
    }

    public function send(string $from, string $address, string $subject, string $message): bool
    {
        try {
            $this->phpmailer->setFrom($from);
            $this->phpmailer->addAddress($address);

            $this->phpmailer->isHTML(true);
            $this->phpmailer->CharSet = 'UTF-8';

            $this->phpmailer->Subject = $subject;
            $this->phpmailer->Body    = '<!DOCTYPE html><html lang="pl-PL"><body>' . $message . '</body></html>';
            $this->phpmailer->AltBody = strip_tags($message);

            $this->phpmailer->send();
            return true;
        } catch (\Exception $e) {
            return "Wiadomość nie mogła zostać wysłana. Błąd: {$this->phpmailer->ErrorInfo}";
        }
    }
}