<?php

namespace PhpMysql\CMS;

class Session
{
    protected $id;
    protected $name;
    protected $role;

    public function __construct()
    {
        session_start();
        $this->id = $_SESSION['id'] ?? 0;
        $this->name = $_SESSION['name'] ?? '';
        $this->role = $_SESSION['role'] ?? 'ogolna';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function create(array $user)
    {
        session_regenerate_id(true);
        $_SESSION['id'] = $user['id'];
        $_SESSION['name'] = $user['first_name'];
        $_SESSION['role'] = $user['role'];
    }

    public function refresh(array $user)
    {
        $this->create($user);
    }

    public function delete()
    {
        $_SESSION = [];
        $param = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 2400,
            $param['path'],
            $param['domain'],
            $param['secure'],
            $param['httponly']
        );
        session_destroy();
    }
}