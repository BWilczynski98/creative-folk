<?php

namespace PhpMysql\CMS;

class Users
{
    protected ?Base $db = null;

    public function __construct(Base $db)
    {
        $this->db = $db;
    }

    public function getById(int $id): mixed
    {
        $sql = "SELECT first_name, last_name, email, created_at, profile_url FROM users WHERE id = :id";
        return $this->db->executeSQL($sql, ['id' => $id])->fetch();
    }

    public function getAll(): array
    {
        $sql = "SELECT first_name, last_name, id FROM users";
        return $this->db->executeSQL($sql)->fetchAll();
    }
    public function create(array $data): bool
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        try {
            $sql = "INSERT INTO `users` (first_name, last_name, email, password)
                VALUES (:first_name, :last_name, :email, :password)";
            $this->db->executeSQL($sql, $data);
            return true;
        } catch (\PDOException $e) {
            if ($e->errorInfo[1] === 1062) {
                return false;
            }
            throw $e;
        }

    }
}