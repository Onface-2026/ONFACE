<?php
// LOGIN.PHP
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'conexao.php';

    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    try {
        $stmt = $pdo->prepare("SELECT id_usuario, primeiro_nome, sobrenome, senha_hash, nivel_acesso 
                               FROM usuarios 
                               WHERE email = ? AND status = 'ativo'");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
            // Login bem-sucedido
            session_start();
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['nome'] = $usuario['primeiro_nome'] . ' ' . $usuario['sobrenome'];
            $_SESSION['nivel_acesso'] = $usuario['nivel_acesso'];

            echo "<script>
                alert('Login realizado com sucesso!');
                window.location.href = 'perfil.html';
            </script>";
            exit;
        } else {
            echo "<script>alert('E-mail ou senha incorretos!');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Erro no login: " . addslashes($e->getMessage()) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnFace - Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<!-- Seu HTML de login aqui (o que já existe) -->
<!-- Vou usar uma versão simples primeiro, depois você pode ajustar -->

<div class="main-card">
    <div class="welcome-side">
        <div class="logo-area">
            <img src="ONFACEorigbranco.png" alt="OnFace">
        </div>
        <h1>Seja bem-vindo!</h1>
    </div>

    <div class="login-side">
        <div class="login-container">
            <h2>Login</h2>
            
            <form method="POST" action="">
                <div class="input-group">
                    <input type="email" name="email" placeholder="E-mail" required>
                </div>
                <div class="input-group">
                    <input type="password" name="senha" placeholder="Senha" required>
                </div>
                
                <button type="submit" class="btn-entrar">Entrar</button>
            </form>

            <div class="footer-links">
                <a href="cadastro.php">Não possui conta? Cadastre-se</a><br><br>
                <a href="forgetpassword.php">Esqueci a senha</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>