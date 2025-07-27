<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Joaoh\H2Tickets\Conexao_db;

session_start();
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $pdo = Conexao_db::conectar();
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_tipo'] = $usuario['tipo'];

        // Redireciona conforme o tipo de usuário
        if ($usuario['tipo'] === 'vendedor') {
            header('Location: dashboard.php');
        } else {
            header('Location: dashboard_cliente.php');
        }
        exit;
    } else {
        $erro = "Email ou senha inválidos!";
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Login</h2>

    <?php if ($erro): ?>
        <p style="color:red;"><?= $erro ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Senha:</label><br>
        <input type="password" name="senha" required><br><br>

        <button type="submit">Entrar</button>
    </form>

    <p>
        Não tem uma conta? <a href="cadastrar_usuario.php">Cadastrar-se</a>
    </p>
</body>
</html>
