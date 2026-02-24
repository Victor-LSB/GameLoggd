<?php
session_start();

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../app/models/User.php';

$database = new Database();
$db = $database->connect();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $userData = $user->login($email, $password);

    if ($userData) {
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['username'] = $userData['username'];
        header("Location: ../index.php");
        exit();
    } else {
        $error = "Email ou senha incorretos.";
        $_SESSION['error'] = $error;
        header("Location: Login.php");
        exit();
    }
}

if (isset($_SESSION['error'])) {
    echo "<p style='color:red;'>" . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Entrar</button>
        <p>Não tem uma conta? <a href="Register.php">Registrar</a></p>
    </form>
</body>
</html>