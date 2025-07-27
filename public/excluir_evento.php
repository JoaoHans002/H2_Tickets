<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use Joaoh\H2Tickets\Conexao_db;

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? 0;
$pdo = Conexao_db::conectar();

$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ? AND id_usuario = ?");
$stmt->execute([$id, $_SESSION['id_usuario']]);
$p = $stmt->fetch();

if (!$p) {
    echo "Produto não encontrado ou acesso negado.";
    exit;
}

$stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
$stmt->execute([$id]);

header("Location: listar_produtos.php");
exit;
