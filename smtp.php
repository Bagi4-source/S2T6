<?php
require './phpMailer/PHPMailer.php';
require './phpMailer/SMTP.php';
require './phpMailer/Exception.php';

// Для более ранних версий PHPMailer
//require_once '/PHPMailer/PHPMailerAutoload.php';
function sendMessage($email, $body)
{
    $mail = new PHPMailer\PHPMailer\PHPMailer;
    $mail->CharSet = 'UTF-8';

// Настройки SMTP
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPDebug = 0;

    $mail->Host = 'ssl://smtp.rambler.ru';
    $mail->Port = 465;
    $mail->Username = 'keitzaharova36757@rambler.ru';
    $mail->Password = 'adUZ742Qpfgu';

// От кого
    $mail->setFrom('keitzaharova36757@rambler.ru', 'Bagi4');

// Кому
    $mail->addAddress($email);

// Тема письма
    $mail->Subject = "Регистрация";

// Тело письма
    $mail->msgHTML($body);

    $mail->send();

}

function sendLogin($email, $password)
{
    $regText = sprintf('<p><strong>Вы успешно зарегистрировались!</strong></p>
<p>Логин: %s</p>
<p>Пароль: %s</p>', $email, $password);
    sendMessage($email, $regText);
}