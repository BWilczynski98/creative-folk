<?php

namespace PhpMysql\CMS;

class CMS
{
    protected ?Base $db = null;
    protected ?Users $user = null;
    protected ?Categories $category = null;
    protected ?Publications $publications = null;
    protected ?Session $session = null;
    protected ?Tokens $tokens = null;

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

    public function session(): Session
    {
        if ($this->session === null) {
            $this->session = new Session();
        }
        return $this->session;
    }

    public function tokens(): Tokens
    {
        if ($this->tokens === null) {
            $this->tokens = new Tokens($this->db);
        }
        return $this->tokens;
    }
}