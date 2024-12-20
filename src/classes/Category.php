<?php

class Category
{
    protected ?Base $db = null;

    public function __construct(Base $db)
    {
        $this->db = $db;
    }

    public function getById(int $id): mixed
    {
        $sql = "SELECT * FROM categories WHERE id = :id";
        return $this->db->executeSQL($sql, ['id' => $id])->fetch();
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM categories";
        return $this->db->executeSQL($sql)->fetchAll();
    }

    public function create(array $category): bool
    {
        try {
            $sql = 'INSERT INTO categories (name, description, navigation) 
                    VALUES (:name, :description, :navigation);';
            $this->db->executeSQL($sql, $category);
            return true;
        } catch (PDOException $e) {
            if ($e->errorInfo[1] === 1062) {
                return false;
            } else {
                throw $e;
            }
        }
    }

    public function update(array $category): bool
    {
        try {
            $sql = 'UPDATE categories
                    SET name = :name, description = :description, navigation = :navigation      
                    WHERE id = :id;';
            $this->db->executeSQL($sql, $category);
            return true;
        } catch (PDOException $e) {
            if ($e->errorInfo[1] === 1062) {
                return false;
            } else {
                throw $e;
            }
        }
    }

    public function delete(int $id): bool
    {
        try {
            $sql = 'DELETE FROM categories
                    WHERE id = :id;';
            $this->db->executeSQL($sql, ['id' => $id]);
            return true;
        } catch (PDOException $e) {
            if ($e->errorInfo[1] === 1451) {
                return false;
            } else {
                throw $e;
            }
        }
    }
}