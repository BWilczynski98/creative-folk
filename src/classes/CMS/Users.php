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
        $sql = "SELECT id, first_name, last_name, email, created_at, profile_url FROM users WHERE id = :id";
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
            $sql = "INSERT INTO users (first_name, last_name, email, password)
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

    public function login(string $email, string $password): mixed
    {
        $sql = 'SELECT id, first_name, last_name, created_at, email, password, profile_url, role
                FROM users
                WHERE email = :email;';
        $user = $this->db->executeSQL($sql, [$email])->fetch();

        if (!$user) {
            return false;
        }

        $password_check = password_verify($password, $user['password']);
        return $password_check ? $user : false;
    }

    public function getUserIdByEmail(string $email): ?int
    {
        $sql = "SELECT id FROM users WHERE email = :email;";
        return $this->db->executeSQL($sql, [$email])->fetchColumn();
    }

    public function updatePassword(int $id, string $new_password): bool
    {
        $hash = password_hash($new_password, PASSWORD_DEFAULT);

        $args = [
            'id' => $id,
            'password' => $hash
        ];

        $sql = "UPDATE users SET password = :password WHERE id = :id;";
        $this->db->executeSQL($sql, $args);
        return true;
    }
}