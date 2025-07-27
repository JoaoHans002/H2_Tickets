<?php

namespace Joaoh\H2Tickets;

use PDO;

class Compra
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Conexao_db::conectar();
    }

    public function registrarCompra(int $idCliente, int $idProduto, int $idVendedor): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO compras (id_usuario, id_cliente, id_produto, status, data_hora) VALUES (?, ?, ?, 'paga', datetime('now'))");
        $stmt->execute([$idVendedor, $idCliente, $idProduto]);

        // Reduz o estoque do produto
        $stmt = $this->pdo->prepare("UPDATE produtos SET quantidade = quantidade - 1, reservado = reservado - 1 WHERE id = ?");
        $stmt->execute([$idProduto]);
    }

    public function listarPorCliente(int $idCliente): array
    {
        $stmt = $this->pdo->prepare("
            SELECT c.id, c.data_compra, p.nome AS produto
            FROM compras c
            JOIN produtos p ON c.produto_id = p.id
            WHERE c.cliente_id = ?
        ");
        $stmt->execute([$idCliente]);
        return $stmt->fetchAll();
    }
}
