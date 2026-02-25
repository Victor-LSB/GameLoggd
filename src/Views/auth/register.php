<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar - GameLoggd</title>
</head>
<body>
    
    <?php if (isset($error)): ?>
        <p style='color:red;'><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <p style='color:green;'><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>

    <form action="index.php?action=register" method="post" id="registerForm">
        <label for="username">Nome de usuário:</label>
        <input type="text" id="username" name="username" required><br><br>
        <p id="messageErrorUsername" style="color:red;"></p>

        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required><br><br>
        <p id="messageErrorPassword" style="color:red;"></p>

        <label for="password_confirm">Confirmar Senha:</label>
        <input type="password" id="password_confirm" name="password_confirm" required><br><br>
        <p id="messageErrorConfirmPassword" style="color:red;"></p>

        <button type="submit">Registrar</button>
        
        <p>Já tem uma conta? <a href="index.php?action=login">Faça login</a></p>
    </form>
<script src="./assets/js/validacaoForm.js"></script>
</body>
</html>