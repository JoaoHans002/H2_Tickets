<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use Joaoh\H2Tickets\Conexao_db;

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'comprador') {
    header("Location: login.php");
    exit;
}

$pdo = Conexao_db::conectar();
$mensagem = '';
$produto = null;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_produto'])) {
    $id_produto = (int)$_GET['id_produto'];
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([$id_produto]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        $mensagem = "Produto não encontrado.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produto'])) {
    $id_produto = (int)$_POST['id_produto'];
    $id_cliente = $_SESSION['usuario_id'];

    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([$id_produto]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produto && ($produto['quantidade'] - $produto['reservado']) > 0) {
        $stmt = $pdo->prepare("UPDATE produtos SET reservado = reservado + 1, data_reserva = ?, id_usuario = ? WHERE id = ?");
        $stmt->execute([time(), $id_cliente, $id_produto]);

        $stmt = $pdo->prepare("INSERT INTO compras (id_usuario, id_cliente, id_produto, status, data_hora) VALUES (?, ?, ?, 'paga', datetime('now'))");
        $stmt->execute([$produto['id_usuario'], $id_cliente, $id_produto]);

        $stmt = $pdo->prepare("UPDATE produtos SET quantidade = quantidade - 1, reservado = reservado - 1 WHERE id = ?");
        $stmt->execute([$id_produto]);

        $mensagem = "Compra finalizada com sucesso!";
    } else {
        $mensagem = "Produto indisponível!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Finalizar Compra</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if ($mensagem): ?>
        <p style="color:blue;"><?= htmlspecialchars($mensagem) ?></p>
        <a href="dashboard_cliente.php">Voltar para eventos</a>
    <?php elseif ($produto): ?>
        <h2><?= htmlspecialchars($produto['nome']) ?></h2>
        <p><?= htmlspecialchars($produto['descricao']) ?></p>
        <p>Ingressos disponíveis: <?= $produto['quantidade'] - $produto['reservado'] ?></p>
        <form method="POST">
            <input type="hidden" name="id_produto" value="<?= $produto['id'] ?>">
            <button type="submit">Finalizar compra</button>
        </form>
        <p style="color:blue;">Ingresso reservado! Finalize a compra em até 2 minutos.</p>
        <a href="dashboard_cliente.php">Cancelar</a>
    <?php else: ?>
        <p>Produto não encontrado.</p>
        <a href="dashboard_cliente.php">Voltar</a>
    <?php endif; ?>
</body>
</html>
