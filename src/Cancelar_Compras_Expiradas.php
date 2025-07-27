<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Joaoh\H2Tickets\Conexao_db;

$pdo = Conexao_db::conectar();

$sql = "
UPDATE produtos SET unidades = unidades + 1
WHERE id IN (
    SELECT id_produto FROM compras 
    WHERE status = 'reservada' AND data_hora <= datetime('now', '-15 minutes')
);

DELETE FROM compras 
WHERE status = 'reservada' AND data_hora <= datetime('now', '-15 minutes');
";

try {
    $pdo->exec($sql);
    echo "Compras expiradas canceladas com sucesso.\n";
} catch (PDOException $e) {
    echo "Erro ao cancelar compras: " . $e->getMessage();
}
