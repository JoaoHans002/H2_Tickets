<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Joaoh\H2Tickets\Conexao_db;

$pdo = Conexao_db::conectar();

// Tabela de usuários (clientes e vendedores)
$sql = "
CREATE TABLE IF NOT EXISTS usuarios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    senha TEXT NOT NULL,
    tipo TEXT NOT NULL
);
";
try {
    $pdo->exec($sql);
    echo "Tabela 'usuarios' criada com sucesso!\n";
} catch (PDOException $e) {
    echo "Erro ao criar tabela de usuarios: " . $e->getMessage() . "\n";
}

// Tabela de produtos/eventos
$sql = "
CREATE TABLE IF NOT EXISTS produtos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT NOT NULL,
    descricao TEXT,
    quantidade INTEGER NOT NULL,
    reservado INTEGER DEFAULT 0,
    data_reserva INTEGER,
    id_usuario INTEGER,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);
";
try {
    $pdo->exec($sql);
    echo "Tabela 'produtos' criada com sucesso!\n";
} catch (PDOException $e) {
    echo "Erro ao criar tabela de produtos: " . $e->getMessage() . "\n";
}

// Tabela de compras
$sql = "
CREATE TABLE IF NOT EXISTS compras (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_usuario INTEGER,
    id_cliente INTEGER,
    id_produto INTEGER,
    status TEXT,
    data_hora TEXT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_cliente) REFERENCES usuarios(id),
    FOREIGN KEY (id_produto) REFERENCES produtos(id)
);
";
try {
    $pdo->exec($sql);
    echo "Tabela 'compras' criada com sucesso!\n";
} catch (PDOException $e) {
    echo "Erro ao criar tabela de compras: " . $e->getMessage() . "\n";
}
