<?php

namespace PhpMysql\CMS;

class CMS
{
    protected ?Base $db = null;
    protected ?Users $user = null;
    protected ?Categories $category = null;
    protected ?Publications $publications = null;

    public function __construct(string $dsn, ?string $username = null, ?string $password = null)
    {
        $this->db = new Base($dsn, $username, $password);
    }

    public function users(): Users
    {
        if ($this->user === null) {
            $this->user = new Users($this->db); // Konstrukt użytkownika, gdzie przekazuje referencje do połączenia się z bazą danych
        }
        return $this->user;
    }

    public function categories(): Categories
    {
        if ($this->category === null) {
            $this->category = new Categories($this->db);
        }
        return $this->category;
    }

    public function publications(): Publications
    {
        if ($this->publications === null) {
            $this->publications = new Publications($this->db);
        }
        return $this->publications;
    }
}