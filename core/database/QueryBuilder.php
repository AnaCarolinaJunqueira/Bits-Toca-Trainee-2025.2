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

    public function countAll($table, $searchColumn = null, $searchTerm = null)
    {
        $sql = "select COUNT(*) from {$table}";
        $parameters = [];

        if($searchColumn && $searchTerm) {
            $sql .= " WHERE posts.{$searchColumn[0]} LIKE :searchTerm or posts.{$searchColumn[1]} LIKE :searchTerm";
            $parameters['searchTerm'] = '%' . $searchTerm . '%';
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($parameters);

            return $stmt->fetchColumn();

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function selectPaginated($table, $limit, $offset, $searchColumn = null, $searchTerm = null)
    {
        $limit = (int) $limit;
        $offset = (int) $offset;
        $parameters = [];
        $whereClause = "";

        if ($searchColumn && $searchTerm) {
            $whereClause = " WHERE posts.{$searchColumn[0]} LIKE :searchTerm or posts.{$searchColumn[1]} LIKE :searchTerm";
            $parameters['searchTerm'] = '%' . $searchTerm . '%';
        }

        $sql = "";

        if($table === 'posts') {
            $sql = "SELECT posts.*, usuarios.NOME as AUTOR_NOME 
                    FROM posts
                    JOIN usuarios ON posts.AUTOR_ID = usuarios.ID
                    {$whereClause}
                    ORDER BY posts.ID ASC 
                    LIMIT {$limit} OFFSET {$offset}";
        } else {
            $sql = "select * from {$table} ORDER BY ID ASC LIMIT {$limit} OFFSET {$offset}";
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($parameters);

            return $stmt->fetchAll(PDO::FETCH_CLASS);

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function findById($table, $id)
    {
        $sql = "select * from {$table} where ID = :id";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id]);

            return $stmt->fetch(PDO::FETCH_OBJ);

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function insert($table, $parameters) 
    {
        $sql = sprintf(
            'insert into %s (%s) values (%s)',
            $table,
            implode(', ', array_keys($parameters)),
            ':' . implode(', :', array_keys($parameters))
        );

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($parameters);

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function update($table, $id, $parameters)
    {
        $setClause = [];
        foreach (array_keys($parameters) as $key) {
            $setClause[] = "{$key} = :{$key}";
        }

        $sql = sprintf(
            'update %s set %s where ID = :id',
            $table,
            implode(', ', $setClause)
        );

        $parameters['id'] = $id;

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($parameters);

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function deleteById($table, $id)
    {
        $sql = "delete from {$table} where ID = :id";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id]);

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}