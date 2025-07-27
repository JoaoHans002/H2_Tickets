<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'vendedor') {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';

use Joaoh\H2Tickets\Conexao_db;

$pdo = Conexao_db::conectar();

$sql = "
SELECT c.id, cl.nome AS cliente, p.nome AS produto, c.status, c.data_hora
FROM compras c
JOIN usuarios cl ON c.id_cliente = cl.id
JOIN produtos p ON c.id_produto = p.id
WHERE c.id_usuario = ?
ORDER BY c.data_hora DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['usuario_id']]);
$compras = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Compras dos meus eventos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Compras dos meus eventos</h2>
    <table border="1" cellpadding="6">
        <tr>
            <th>Cliente</th>
            <th>Produto</th>
            <th>Status</th>
            <th>Data</th>
        </tr>
        <?php foreach ($compras as $c): ?>
        <tr>
            <td><?= htmlspecialchars($c['cliente']) ?></td>
            <td><?= htmlspecialchars($c['produto']) ?></td>
            <td><?= htmlspecialchars($c['status']) ?></td>
            <td><?= htmlspecialchars($c['data_hora']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <a href="dashboard.php">Voltar</a>
</body>
</html>
