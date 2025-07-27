<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Joaoh\H2Tickets\Conexao_db;

$pdo = Conexao_db::conectar();

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
    echo "Tabela 'usuarios' criada com sucesso!";
} catch (PDOException $e) {
    echo "Erro ao criar tabela: " . $e->getMessage();
}


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
    echo "Erro ao criar tabela de produtos: " . $e->getMessage();
}


$sql = "
CREATE TABLE IF NOT EXISTS clientes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT NOT NULL,
    email TEXT NOT NULL,
    id_usuario INTEGER NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);
";

try {
    $pdo->exec($sql);
    echo "Tabela 'clientes' criada com sucesso!\n";
} catch (PDOException $e) {
    echo "Erro ao criar tabela de clientes: " . $e->getMessage();
}


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
    echo "Erro ao criar tabela de compras: " . $e->getMessage();
}

$sql = "ALTER TABLE usuarios ADD COLUMN tipo TEXT NOT NULL DEFAULT 'comprador';";

try {
    $pdo->exec($sql);
    echo "Coluna 'tipo' adicionada à tabela 'usuarios' com sucesso!";
} catch (PDOException $e) {
    echo "Erro ao adicionar coluna 'tipo' à tabela 'usuarios': " . $e->getMessage();
}

