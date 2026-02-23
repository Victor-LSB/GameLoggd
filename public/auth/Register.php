<?php
session_start();

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../app/models/User.php';

$database = new Database();
$db = $database->connect();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($user->register($username, $email, $password)) {
        header("Location: Login.php");
        exit();
    } else {
        $error = "Email ou nome de usuário já existe.";
        echo "<script>alert('$error');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <form action="" method="post">
        <label for="username">Nome de usuário:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Registrar</button>
        <p>Já tem uma conta? <a href="Login.php">Faça login</a></p>
    </form>
</body>
</html>