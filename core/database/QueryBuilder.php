<?php

namespace App\Core\Database;

use PDO, Exception;

class QueryBuilder
{
    protected $pdo;


    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function selectAll($table)
    {
        $sql = "select * from {$table}";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_CLASS);

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function verificaLogin($email, $senha)
    {
        $sql = sprintf("SELECT * FROM usuarios WHERE EMAIL = :email AND SENHA = :senha");

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'email' => $email,
                'senha' => $senha
            ]);

            $usuario = $stmt->fetch(PDO::FETCH_OBJ);

            return $usuario;

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}