<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');

$host = 'localhost';
$dbname = 'u82352';
$username = 'u82352';
$password = '9562557';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка БД: " . $e->getMessage());
}

$allowed_languages = ['Pascal', 'C', 'C++', 'JavaScript', 'PHP', 'Python', 'Java', 'Go'];

function setCookieJson($name, $value, $expire) {
    setcookie($name, json_encode($value), $expire, '/', '', false, true);
}
function getCookieJson($name) {
    return isset($_COOKIE[$name]) ? json_decode($_COOKIE[$name], true) : null;
}

function generateLogin() { return 'user' . rand(1000, 9999); }
function generatePassword($length = 10) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
    return substr(str_shuffle($chars), 0, $length);
}

$messages = [];
if (isset($_SESSION['success_message'])) {
    $messages[] = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

$errors = getCookieJson('form_errors') ?? [];
$values = getCookieJson('form_values') ?? [];
$is_logged = isset($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login_form'])) {
        $login = trim($_POST['login'] ?? '');
        $pass  = $_POST['password'] ?? '';

        $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch();

        if ($user && password_verify($pass, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['application_id'];
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['success_message'] = '<span style="color:red">Ошибка: Неверный логин или пароль</span>';
            header('Location: index.php');
            exit;
        }
    } 
    else {
        $fio = trim($_POST['fio'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $birth_date = $_POST['birth_date'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $languages = $_POST['languages'] ?? [];
        $biography = trim($_POST['biography'] ?? '');
        $contract = isset($_POST['contract']) ? 1 : 0;

        $errors = [];
        $values = ['fio'=>$fio,'phone'=>$phone,'email'=>$email,'birth_date'=>$birth_date,'gender'=>$gender,'languages'=>$languages,'biography'=>$biography,'contract'=>$contract];

        if (empty($fio) || !preg_match('/^[a-zA-Zа-яА-ЯёЁ\s]+$/u', $fio)) $errors['fio'] = 'Введите корректное ФИО';
        if (empty($phone) || !preg_match('/^\+?[\d\s\-\(\)]{10,20}$/', $phone)) $errors['phone'] = 'Неверный формат телефона';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Неверный email';
        if (empty($birth_date)) $errors['birth_date'] = 'Укажите дату рождения';
        if (!in_array($gender, ['male', 'female'])) $errors['gender'] = 'Выберите пол';
        if (empty($languages)) $errors['languages'] = 'Выберите хотя бы один язык';
        if ($contract === 0) $errors['contract'] = 'Необходимо согласие с контрактом';

        if (!empty($errors)) {
            setCookieJson('form_errors', $errors, time() + 3600);
            setCookieJson('form_values', $values, time() + 3600);
            header('Location: index.php');
            exit;
        }

        setcookie('form_errors', '', time() - 3600, '/');

        $is_new = !isset($_SESSION['user_id']);

        if ($is_new) {
            $login = generateLogin();
            $plain_pass = generatePassword();
            $hash = password_hash($plain_pass, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO applications (fio, phone, email, birth_date, gender, biography, contract) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$fio, $phone, $email, $birth_date, $gender, $biography, $contract]);
            $app_id = $pdo->lastInsertId();

            $stmt = $pdo->prepare("INSERT INTO users (application_id, login, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$app_id, $login, $hash]);

            foreach ($languages as $lang) {
                $stmt = $pdo->prepare("INSERT INTO application_languages (application_id, language_id) VALUES (?, (SELECT id FROM languages WHERE name = ?))");
                $stmt->execute([$app_id, $lang]);
            }

            $_SESSION['user_id'] = $app_id;
            $_SESSION['success_message'] = "Данные сохранены!<br>Логин: <b>$login</b><br>Пароль: <b>$plain_pass</b>";
        } else {
            $app_id = $_SESSION['user_id'];
            $stmt = $pdo->prepare("UPDATE applications SET fio=?, phone=?, email=?, birth_date=?, gender=?, biography=?, contract=? WHERE id=?");
            $stmt->execute([$fio, $phone, $email, $birth_date, $gender, $biography, $contract, $app_id]);

            $stmt = $pdo->prepare("DELETE FROM application_languages WHERE application_id = ?");
            $stmt->execute([$app_id]);
            foreach ($languages as $lang) {
                $stmt = $pdo->prepare("INSERT INTO application_languages (application_id, language_id) VALUES (?, (SELECT id FROM languages WHERE name = ?))");
                $stmt->execute([$app_id, $lang]);
            }
            $_SESSION['success_message'] = "Данные успешно обновлены!";
        }

        setCookieJson('form_values', $values, time() + 365*24*3600);
        header('Location: index.php');
        exit;
    }
}

if ($is_logged && empty($errors)) {
    $stmt = $pdo->prepare("SELECT * FROM applications WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch();
    if ($row) {
        $values = $row;
        $stmt = $pdo->prepare("SELECT l.name FROM application_languages al JOIN languages l ON al.language_id = l.id WHERE al.application_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $values['languages'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}

include('form.php');
?>
