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

    public function countAll($table, $searchColumn = null, $searchTerm = null, $autor_id = null, $is_admin = null)
    {
        $sql = "select COUNT(*) from {$table}";
        $parameters = [];

        if($searchColumn && $searchTerm) {
            $sql .= " WHERE {$searchColumn[0]} LIKE :searchTerm OR {$searchColumn[1]} LIKE :searchTerm";
            $parameters['searchTerm'] = '%' . $searchTerm . '%';
        }

        if ($autor_id && $is_admin !== 1) {
            $sql .= " WHERE AUTOR_ID = :autor_id";
            $parameters['autor_id'] = $autor_id;
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($parameters);
            return $stmt->fetchColumn();

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function selectPaginated($table, $limit, $offset, $searchColumn = null, $searchTerm = null, $autor_id = null, $is_admin = null)
    {
        $limit = (int) $limit;
        $offset = (int) $offset;
        $parameters = [];
        $whereClause = "";
        $whereUsuario = "";

        if ($searchColumn && $searchTerm) {
            $whereClause = " WHERE {$searchColumn[0]} LIKE :searchTerm OR {$searchColumn[1]} LIKE :searchTerm";
            $parameters['searchTerm'] = '%' . $searchTerm . '%';
        }

        if ($autor_id && $is_admin !== 1) {
            $whereUsuario = " WHERE AUTOR_ID = :autor_id";
            $parameters['autor_id'] = $autor_id;
        }

        $sql = "";

        if($table === 'posts') {
            $sql = "SELECT posts.*, usuarios.NOME as AUTOR_NOME 
                    FROM posts
                    JOIN usuarios ON posts.AUTOR_ID = usuarios.ID
                    {$whereClause} {$whereUsuario}
                    ORDER BY posts.ID ASC 
                    LIMIT {$limit} OFFSET {$offset}";
        } else {
            $sql = "select * from {$table} {$whereClause} {$whereUsuario} ORDER BY ID ASC LIMIT {$limit} OFFSET {$offset}";
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($parameters);

            return $stmt->fetchAll(PDO::FETCH_CLASS);

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function selectPublicPosts($limit, $offset, $searchTerm = null, $category = null)
    {
        $parameters = [];
        $whereClauses = [];

        $sql = "SELECT p.*, u.NOME as AUTOR_NOME 
                FROM Posts p
                JOIN Usuarios u ON p.AUTOR_ID = u.ID";

        if ($searchTerm) {
            $whereClauses[] = "p.TITULO LIKE :searchTerm";
            $parameters['searchTerm'] = '%' . $searchTerm . '%';
        }

        if ($category) {
            $whereClauses[] = "p.CATEGORIA = :category";
            $parameters['category'] = $category;
        }

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }

        $sql .= " ORDER BY p.DATA_POSTAGEM DESC LIMIT {$limit} OFFSET {$offset}";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($parameters);
            return $stmt->fetchAll(PDO::FETCH_CLASS);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function countPublicPosts($searchTerm = null, $category = null)
    {
        $sql = "SELECT COUNT(*) FROM Posts";
        $parameters = [];
        $whereClauses = [];

        if ($searchTerm) {
            $whereClauses[] = "TITULO LIKE :searchTerm";
            $parameters['searchTerm'] = '%' . $searchTerm . '%';
        }
        if ($category) {
            $whereClauses[] = "CATEGORIA = :category";
            $parameters['category'] = $category;
        }

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($parameters);
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function getPostWithAuthor($id)
    {
        $sql = "SELECT p.*, u.NOME as AUTOR_NOME, u.AVATAR as AUTOR_AVATAR
                FROM Posts p
                JOIN Usuarios u ON p.AUTOR_ID = u.ID
                WHERE p.ID = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function selectFeaturedPosts($limit = 3)
    {
        $sql = "SELECT p.*, COUNT(c.ID) as total_likes
                FROM Posts p
                LEFT JOIN Curtidas c ON p.ID = c.POST_ID
                GROUP BY p.ID
                ORDER BY total_likes DESC
                LIMIT {$limit}";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_CLASS);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function selectRecentPosts($limit = 18) 
    {
        $sql = "SELECT * FROM Posts
                ORDER BY DATA_POSTAGEM DESC
                LIMIT {$limit}";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
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
    
    public function selectPaginatedDiscussions($limit, $offset, $searchTerm = null, $category = null)
    {
        $parameters = [];
        $whereClauses = [];

        $sql = "SELECT d.*, u.NOME as AUTOR_NOME, COUNT(r.ID) as TOTAL_RESPOSTAS,
                COALESCE(MAX(r.DATA_CRIACAO), d.DATA_POSTAGEM) as ULTIMA_ATIVIDADE 
                FROM Discussoes d
                JOIN Usuarios u ON d.AUTOR_ID = u.ID
                LEFT JOIN Respostas_Discussoes r ON d.ID = r.DISCUSSAO_ID";

        if ($searchTerm) {
            $whereClauses[] = "d.TITULO LIKE :searchTerm";
            $parameters['searchTerm'] = '%' . $searchTerm . '%';
        }

        if ($category) {
            $whereClauses[] = "d.CATEGORIA = :category";
            $parameters['category'] = $category;
        }

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }

        $sql .= " GROUP BY d.ID ORDER BY ULTIMA_ATIVIDADE DESC LIMIT {$limit} OFFSET {$offset}";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($parameters);
            return $stmt->fetchAll(\PDO::FETCH_CLASS);
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function countDiscussions($searchTerm = null, $category = null)
    {
        $sql = "SELECT COUNT(*) FROM Discussoes";
        $parameters = [];
        $whereClauses = [];

        if ($searchTerm) {
            $whereClauses[] = "TITULO LIKE :searchTerm";
            $parameters['searchTerm'] = '%' . $searchTerm . '%';
        }
        if ($category) {
            $whereClauses[] = "CATEGORIA = :category";
            $parameters['category'] = $category;
        }

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($parameters);
            return $stmt->fetchColumn();
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function getDiscussionReplies($discussionId)
    {
        $sql = "SELECT r.*, u.NOME as AUTOR_NOME, u.AVATAR 
                FROM Respostas_Discussoes r
                JOIN Usuarios u ON r.USER_ID = u.ID
                WHERE r.DISCUSSAO_ID = :id
                ORDER BY r.DATA_CRIACAO ASC";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $discussionId]);
            return $stmt->fetchAll(\PDO::FETCH_CLASS);
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function getDiscussionById($id)
    {
        $sql = "SELECT d.*, u.NOME as AUTOR_NOME, u.AVATAR 
                FROM Discussoes d
                JOIN Usuarios u ON d.AUTOR_ID = u.ID
                WHERE d.ID = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(\PDO::FETCH_OBJ);
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
    
    public function countCategories() {
        $sql = "SELECT CATEGORIA, COUNT(*) as total FROM Discussoes GROUP BY CATEGORIA";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        } catch (\Exception $e) {
            return [];
        }
    }

    public function findByEmail($table, $email)
    {
        $sql = "select * from {$table} where EMAIL = :email";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['email' => $email]);

            return $stmt->fetch(PDO::FETCH_OBJ);

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

    public function countLikesPost($post_id)
    {
        $sql = "select COUNT(*) from curtidas WHERE POST_ID = :post_id";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['post_id' => $post_id]);
            return $stmt->fetchColumn();

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function findLike($post_id, $user_id)
    {
        $sql = "SELECT * from curtidas WHERE POST_ID = :post_id AND USER_ID = :user_id";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'post_id' => $post_id,
                'user_id' => $user_id
            ]);
            return $stmt->fetch(PDO::FETCH_OBJ);

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function getPostComments($postId)
    {
        $sql = "SELECT c.*, u.NOME as AUTOR_NOME, u.AVATAR 
                FROM Comentarios c
                JOIN Usuarios u ON c.USER_ID = u.ID
                WHERE c.POST_ID = :post_id
                ORDER BY c.DATA_CRIACAO DESC";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['post_id' => $postId]);
            return $stmt->fetchAll(PDO::FETCH_CLASS);
        } catch (Exception $e) {
            return [];
        }
    }
}