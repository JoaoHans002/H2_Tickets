<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use Joaoh\H2Tickets\Conexao_db;

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'vendedor') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $quantidade = (int) ($_POST['quantidade'] ?? 0);
    $id_usuario = $_SESSION['usuario_id'];

    $pdo = Conexao_db::conectar();
    $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, quantidade, id_usuario) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nome, $descricao, $quantidade, $id_usuario]);

    header("Location: listar_eventos.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Novo Produto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Cadastrar Evento</h2>
    <form method="POST">
        <label>Nome:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>Descrição:</label><br>
        <textarea name="descricao"></textarea><br><br>

        <label>Quantidade:</label><br>
        <input type="number" name="quantidade" required><br><br>

        <button type="submit">Cadastrar</button>
    </form>
    <a href="dashboard.php">Voltar</a>
</body>
</html>
