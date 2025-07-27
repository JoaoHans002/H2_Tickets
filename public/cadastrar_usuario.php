<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Joaoh\H2Tickets\Conexao_db;

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $tipo = $_POST['tipo'] ?? 'comprador';

    if (!$nome || !$email || !$senha || !$tipo) {
        $erro = "Preencha todos os campos!";
    } else {
        try {
            $pdo = Conexao_db::conectar();
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $nome,
                $email,
                password_hash($senha, PASSWORD_DEFAULT),
                $tipo
            ]);
            header("Location: login.php");
            exit;
        } catch (PDOException $e) {
            $erro = "Erro ao cadastrar usuário: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Cadastrar Usuário</h2>
    <?php if ($erro): ?>
        <p style="color:red;"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>
    <form method="POST">
        <label>Nome:</label><br>
        <input type="text" name="nome" required><br><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <label>Senha:</label><br>
        <input type="password" name="senha" required><br><br>
        <label>Tipo:</label><br>
        <select name="tipo" required>
            <option value="vendedor">Vender ingressos</option>
            <option value="comprador">Comprar ingressos</option>
        </select><br><br>
        <button type="submit">Cadastrar</button>
    </form>
    <a href="login.php">Voltar</a>
</body>
</html>