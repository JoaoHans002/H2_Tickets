<?php

namespace Joaoh\H2Tickets;

use PDO;
use PDOException;

class Conexao_db
{
    private static $pdo;

    public static function conectar()
    {
        if (!self::$pdo) {
            try {
                self::$pdo = new PDO('sqlite:' . __DIR__ . '/../public/db.sqlite');
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
            }
        }

        return self::$pdo;
    }
}