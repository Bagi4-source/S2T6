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
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        $user = $db->prepare("Select * from users where id = ?");
        $relations = $db->prepare("Select * from relations where user_id = ?");

        $user->execute([$id]);
        if (!$user) {
            print('Error : ' . $user->errorInfo());
        }
        $row = $user->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $relations->execute([$id]);
            if (!$relations) {
                print('Error : ' . $relations->errorInfo());
            }
            $abilki = array();
            while ($abilka = $relations->fetch(PDO::FETCH_ASSOC)) {
                $abilki[] = $abilka['ability_id'];
            }
            $values['fio'] = $row['name'];
            $values['email'] = $row['email'];
            $values['checkbox'] = $row['checkbox'];
            $values['abilities'] = $abilki;
            $values['limbs'] = $row['limbs'];
            $values['gender'] = $row['gender'];
            $values['year'] = $row['year'];
            $values['biography'] = $row['biography'];
        }
    } catch (PDOException $e) {
        print('Error : ' . $e->getMessage());
        exit();
    }
    include('form.php');
}

$abilities = [];
$abilities_query = $db->query("SELECT id FROM abilities;");
while ($row = $abilities_query->fetch(PDO::FETCH_ASSOC)) {
    array_push($abilities, $row['id']);
}

function validateAbilities()
{
    global $abilities;
    foreach ($_POST['abilities'] as $ability_id) {
        if (!in_array($ability_id, $abilities))
            return false;
    }
    return true;
}

$errors = FALSE;
if (empty($_POST['fio'])) {
    setcookie('fio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
} else {
    setcookie('fio_value', $_POST['fio'], time() + 30 * 24 * 60 * 60);
}

if (empty($_POST['email']) || !preg_match('/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/', $_POST['email'])) {
    setcookie('email_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
} else {
    setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
}

if (empty($_POST['checkbox']) || !($_POST['checkbox'] == 'on' || $_POST['checkbox'] == 1)) {
    setcookie('checkbox_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
} else {
    setcookie('checkbox_value', $_POST['checkbox'], time() + 30 * 24 * 60 * 60);
}

if (empty($_POST['abilities']) || !is_array($_POST['abilities']) || !validateAbilities()) {
    setcookie('abilities_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
} else {
    setcookie('abilities_value', serialize($_POST['abilities']), time() + 30 * 24 * 60 * 60);
}

if (empty($_POST['limbs']) || !is_numeric($_POST['limbs']) || $_POST['limbs'] > 4 || $_POST['limbs'] < 0) {
    setcookie('limbs_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
} else {
    setcookie('limbs_value', $_POST['limbs'], time() + 30 * 24 * 60 * 60);
}

if (empty($_POST['gender']) || $_POST['gender'] != 'м' && $_POST['gender'] != 'ж') {
    setcookie('gender_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
} else {
    setcookie('gender_value', $_POST['gender'], time() + 30 * 24 * 60 * 60);
}

if (empty($_POST['year'])) {
    setcookie('year_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
} else {
    setcookie('year_value', $_POST['year'], time() + 30 * 24 * 60 * 60);
}

if ($errors) {
    header('Location: index.php');
    exit();
}

try {
    $stmt = $db->prepare("UPDATE users SET name = ?, year = ?, biography = ?, email = ?, limbs = ?, gender = ?, checkbox = ? WHERE id = ?");
    $stmt->execute([$_POST['fio'], $_POST['year'], $_POST['biography'], $_POST['email'], $_POST['limbs'], $_POST['gender'], 1, $id]);
    if (!$stmt) {
        print('Error : ' . $stmt->errorInfo());
    }
} catch (PDOException $e) {
    print('Error : ' . $e->getMessage());
    exit();
}
// Удаляем способности
try {
    $stmt = $db->prepare("DELETE FROM relations WHERE user_id = ?");
    $stmt->execute([$id]);
    if (!$stmt) {
        print('Error : ' . $stmt->errorInfo());
    }
} catch (PDOException $e) {
    print('Error : ' . $e->getMessage());
    exit();
}
// Добавляем способности
foreach ($_POST['abilities'] as $ability_id) {
    try {
        $stmt = $db->prepare("INSERT INTO relations SET user_id = ?, ability_id = ?");
        $stmt->execute([$id, $ability_id]);
        if (!$stmt) {
            print('Error : ' . $stmt->errorInfo());
        }
    } catch (PDOException $e) {
        print('Error : ' . $e->getMessage());
        exit();
    }
}
// Обновляем почту для логина
try {
    $stmt = $db->prepare("UPDATE logins SET login = ? WHERE user_id = ?");
    $stmt->execute([$_POST['email'], $id]);
    if (!$stmt) {
        print('Error : ' . $stmt->errorInfo());
    }
} catch (PDOException $e) {
    print('Error : ' . $e->getMessage());
    exit();
}
setcookie('save', '1');

header('Location: ./');