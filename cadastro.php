<?php
// CADASTRO.PHP
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'conexao.php';

    $primeiro_nome = trim($_POST['primeiro_nome']);
    $sobrenome     = trim($_POST['sobrenome']);
    $email         = trim($_POST['email']);
    $celular       = trim($_POST['celular']);
    $senha         = $_POST['senha'];
    $confirm_senha = $_POST['confirm_senha'];

    if ($senha !== $confirm_senha) {
        echo "<script>alert('As senhas não coincidem!'); history.back();</script>";
        exit;
    }

    if (strlen($senha) < 6) {
        echo "<script>alert('A senha deve ter no mínimo 6 caracteres!'); history.back();</script>";
        exit;
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (primeiro_nome, sobrenome, email, celular, senha_hash) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$primeiro_nome, $sobrenome, $email, $celular, $senha_hash]);

        echo "<script>
            alert('✅ Cadastro realizado com sucesso!');
            window.location.href = 'login.html';
        </script>";
        exit;

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "<script>alert('Este e-mail ou celular já está cadastrado!');</script>";
        } else {
            echo "<script>alert('Erro: " . addslashes($e->getMessage()) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnFace - Cadastro</title>
    <link rel="stylesheet" href="cadastro.css">
</head>
<body>

<div class="card">
    <div class="header">
        <h1>Cadastre-se</h1>
        <div class="logo-container">
            <img src="ONFACEorigbranco.png" alt="OnFace">
        </div>
    </div>

    <form method="POST" action="">
        <div class="form-grid">
            <div class="input-group">
                <label>Primeiro nome</label>
                <input type="text" name="primeiro_nome" placeholder="Digite seu primeiro nome" required>
            </div>
            <div class="input-group">
                <label>Sobrenome</label>
                <input type="text" name="sobrenome" placeholder="Digite seu sobrenome" required>
            </div>
            <div class="input-group">
                <label>E-mail</label>
                <input type="email" name="email" placeholder="Digite seu e-mail" required>
            </div>
            <div class="input-group">
                <label>Celular</label>
                <input type="text" name="celular" placeholder="(xx) xxxxx-xxxx" required>
            </div>
            <div class="input-group">
                <label>Senha</label>
                <input type="password" name="senha" placeholder="Digite sua senha" required>
            </div>
            <div class="input-group">
                <label>Confirmar senha</label>
                <input type="password" name="confirm_senha" placeholder="Confirme sua senha" required>
            </div>
        </div>

        <div class="footer">
            <button type="submit" class="btn-concluir">Concluir cadastro</button>
        </div>
    </form>
</div>

</body>
</html>