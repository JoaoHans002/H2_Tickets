<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use Joaoh\H2Tickets\Conexao_db;

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'vendedor') {
    header('Location: login.php');
    exit;
}

$pdo = Conexao_db::conectar();
$id_usuario = $_SESSION['usuario_id'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Busca o evento
$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ? AND id_usuario = ?");
$stmt->execute([$id, $id_usuario]);
$evento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$evento) {
    echo "Evento não encontrado ou você não tem permissão para editar.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $quantidade = (int) ($_POST['quantidade'] ?? 0);

    $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, descricao = ?, quantidade = ? WHERE id = ? AND id_usuario = ?");
    $stmt->execute([$nome, $descricao, $quantidade, $id, $id_usuario]);

    header("Location: listar_eventos.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Evento</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Editar Evento</h2>
    <form method="POST">
        <label>Nome:</label><br>
        <input type="text" name="nome" value="<?= htmlspecialchars($evento['nome']) ?>" required><br><br>

        <label>Descrição:</label><br>
        <textarea name="descricao"><?= htmlspecialchars($evento['descricao']) ?></textarea><br><br>

        <label>Quantidade:</label><br>
        <input type="number" name="quantidade" value="<?= $evento['quantidade'] ?>" required><br><br>

        <button type="submit">Salvar</button>
    </form>
    <a href="listar_eventos.php">Voltar</a>
</body>
</html>
