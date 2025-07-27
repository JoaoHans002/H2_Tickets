<?php

namespace Joaoh\H2Tickets;

use PDO;

class Produto
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Conexao_db::conectar();
    }

    public function listar(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM produtos");
        return $stmt->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM produtos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function reduzirEstoque(int $id): void
    {
        $stmt = $this->pdo->prepare("UPDATE produtos SET quantidade = quantidade - 1 WHERE id = ? AND quantidade > 0");
        $stmt->execute([$id]);
    }
}
