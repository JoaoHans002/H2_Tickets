<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'vendedor') {
    header('Location: login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</h2>

    <ul>
        <li><a href="cadastrar_evento.php">Cadastrar novo evento</a></li>
        <li><a href="listar_eventos.php">Meus Eventos</a></li>
        <li><a href="listar_compras.php">Compras dos meus eventos</a></li>
        <li><a href="logout.php">Sair</a></li>
    </ul>
</body>
</html>
