<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GameLoggd</title>
</head>
<body>
    
    <?php if (isset($error)): ?>
        <p style='color:red;'><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form action="index.php?action=login" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <button type="submit">Entrar</button>
        
        <p>Não tem uma conta? <a href="index.php?action=register">Registrar</a></p>
    </form>
    
</body>
</html>