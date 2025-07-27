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

$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Meus Produtos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Seus Eventos</h2>

    <a href="cadastrar_evento.php">Novo Evento</a><br><br>

    <table border="1" cellpadding="6">
        <tr>
            <th>Nome</th>
            <th>Quantidade</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($eventos as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nome']) ?></td>
                <td><?= $p['quantidade'] ?></td>
                <td>
                    <a href="editar_evento.php?id=<?= $p['id'] ?>">Editar</a> |
                    <a href="excluir_evento.php?id=<?= $p['id'] ?>" onclick="return confirm('Excluir?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <a href="dashboard.php">Voltar</a>
</body>
</html>
