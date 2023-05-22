<?php
$user = 'u52803';
$pass = '9294062';
$db = new PDO('mysql:host=localhost;dbname=u52803', $user, $pass, [PDO::ATTR_PERSISTENT => true]);

function admin_error()
{
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
}

if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW'])) {
    admin_error();
} else {
    $stmt = $db->prepare("SELECT * FROM admins WHERE login = ? and password = ?");
    $login = $_SERVER['PHP_AUTH_USER'];
    $password = md5($_SERVER['PHP_AUTH_PW']);

    $stmt->execute([$login, $password]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        admin_error();
    }
}

print('Вы успешно авторизовались и видите защищенные паролем данные.');

// *********
// Здесь нужно прочитать отправленные ранее пользователями данные и вывести в таблицу.
// Реализовать просмотр и удаление всех данных.
// *********
$users = $db->query("SELECT * FROM users;");

include('table.php');