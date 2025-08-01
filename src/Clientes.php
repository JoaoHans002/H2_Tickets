<?php

namespace Joaoh\H2Tickets;

use PDO;

class Cliente
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Conexao_db::conectar();
    }

    public function buscarPorEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM clientes WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM clientes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}
