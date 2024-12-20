<?php

class CMS
{
    protected ?Base $db = null;
    protected ?User $user = null;
    protected ?Category $category = null;

    public function __construct(string $dsn, ?string $username = null, ?string $password = null)
    {
        $this->db = new Base($dsn, $username, $password);
    }

    public function user(): User
    {
        if ($this->user === null) {
            $this->user = new User($this->db); // Konstrukt użytkownika, gdzie przekazuje referencje do połączenia się z bazą danych
        }
        return $this->user;
    }

    public function category(): Category
    {
        if ($this->category === null) {
            $this->category = new Category($this->db);
        }
        return $this->category;
    }
}