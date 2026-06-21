<?php
// RESETPASSWORD.PHP
include 'conexao.php';

$token = $_GET['token'] ?? '';

if (empty($token)) {
    die("Token inválido!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nova_senha = $_POST['nova_senha'];
    $confirmar  = $_POST['confirmar_senha'];

    if ($nova_senha !== $confirmar) {
        echo "<script>alert('As senhas não coincidem!');</script>";
    } elseif (strlen($nova_senha) < 6) {
        echo "<script>alert('A senha deve ter pelo menos 6 caracteres!');</script>";
    } else {
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE usuarios SET senha_hash = ?, reset_token = NULL, reset_token_expira = NULL 
                               WHERE reset_token = ? AND reset_token_expira > NOW()");
        $stmt->execute([$senha_hash, $token]);

        if ($stmt->rowCount() > 0) {
            echo "<script>
                alert('✅ Senha alterada com sucesso!');
                window.location.href = 'login.php';
            </script>";
        } else {
            echo "<script>alert('Token inválido ou expirado!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="forgetpassword.css">
</head>
<body>

<div class="container">
    <h1>Redefinir Senha</h1>
    
    <form method="POST" action="">
        <div class="input-box">
            <input type="password" name="nova_senha" placeholder="Nova senha" required>
        </div>
        <div class="input-box">
            <input type="password" name="confirmar_senha" placeholder="Confirmar nova senha" required>
        </div>
        <button type="submit">Salvar Nova Senha</button>
    </form>
</div>

</body>
</html>