<?php

namespace CMS;

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

}