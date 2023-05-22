<?php

if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] != 'admin' ||
    md5($_SERVER['PHP_AUTH_PW']) != md5('123')) {
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
}

$id = $_GET['id'];

$user = 'u52803';
$pass = '9294062';
$db = new PDO('mysql:host=localhost;dbname=u52803', $user, $pass, [PDO::ATTR_PERSISTENT => true]);

try {
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    if (!$stmt) {
        print('Error : ' . $stmt->errorInfo());
    }
} catch (PDOException $e) {
    print('Error : ' . $e->getMessage());
    exit();
}
header('Location: ../');
