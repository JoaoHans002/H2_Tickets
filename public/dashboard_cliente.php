<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
use Joaoh\H2Tickets\Conexao_db;

session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'comprador') {
    header('Location: login.php');
    exit;
}

$pdo = Conexao_db::conectar();
$mensagem = '';

$agora = time();
$timeout = 120;
$pdo->exec("UPDATE produtos SET reservado = 0, data_reserva = NULL, id_usuario = NULL WHERE reservado = 1 AND data_reserva IS NOT NULL AND ($agora - data_reserva) > $timeout");

if (isset($_POST['comprar'])) {
    $produto_id = (int)$_POST['produto_id'];
    $stmt = $pdo->prepare("SELECT quantidade, reservado, id_usuario FROM produtos WHERE id = ?");
    $stmt->execute([$produto_id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produto && ($produto['quantidade'] - $produto['reservado']) > 0) {
        $stmt = $pdo->prepare("UPDATE produtos SET reservado = reservado + 1, data_reserva = ?, id_usuario = ? WHERE id = ?");
        $stmt->execute([time(), $_SESSION['usuario_id'], $produto_id]);

        $stmt = $pdo->prepare("INSERT INTO compras (id_usuario, id_cliente, id_produto, status, data_hora) VALUES (?, ?, ?, 'reservada', datetime('now'))");
        $stmt->execute([$produto['id_usuario'], $_SESSION['usuario_id'], $produto_id]);

        $mensagem = "Ingresso reservado! Finalize a compra em até 2 minutos.";
    } else {
        $mensagem = "Produto indisponível!";
    }
}

if (isset($_POST['finalizar'])) {
    $produto_id = (int)$_POST['produto_id'];
    $id_cliente = $_SESSION['usuario_id'];

    $stmt = $pdo->prepare("SELECT id_usuario FROM produtos WHERE id = ?");
    $stmt->execute([$produto_id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produto) {
        $id_vendedor = $produto['id_usuario'];
        $compra = new \Joaoh\H2Tickets\Compra();
        $compra->registrarCompra($id_cliente, $produto_id, $id_vendedor);
        $mensagem = "Compra finalizada!";
    }
}

$eventos = $pdo->query("
    SELECT p.*, u.nome AS nome_vendedor
    FROM produtos p
    JOIN usuarios u ON p.id_usuario = u.id
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard Cliente</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></h2>
    <h3>Eventos disponíveis</h3>
    <?php if ($mensagem): ?>
        <p style="color:blue;"><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>
    <table border="1" cellpadding="8">
        <tr>
            <th>Evento</th>
            <th>Descrição</th>
            <th>Ingressos Disponíveis</th>
            <th>Publicado por</th>
            <th>Ação</th>
        </tr>
        <?php foreach ($eventos as $evento): 
            $disponiveis = $evento['quantidade'] - $evento['reservado'];
        ?>
        <tr>
            <td><?= htmlspecialchars($evento['nome']) ?></td>
            <td><?= htmlspecialchars($evento['descricao']) ?></td>
            <td><?= $disponiveis > 0 ? $disponiveis : 0 ?></td>
            <td><?= htmlspecialchars($evento['nome_vendedor']) ?></td>
            <td>
                <?php if ($disponiveis > 0): ?>
                    <form method="GET" action="comprar.php" style="display:inline;">
                        <input type="hidden" name="id_produto" value="<?= $evento['id'] ?>">
                        <button type="submit">Comprar</button>
                    </form>
                <?php else: ?>
                    <span style="color:red;">Produto indisponível</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <a href="logout.php">Sair</a>
</body>
</html>