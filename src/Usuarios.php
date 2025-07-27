<?php

namespace Joaoh\H2Tickets;

use PDO;

class Usuario
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Conexao_db::conectar();
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function listar(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM usuarios");
        return $stmt->fetchAll();
    }
}
