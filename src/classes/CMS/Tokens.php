<?php

namespace PhpMysql\CMS;

class Tokens
{
    protected ?Base $db = null;

    public function __construct(Base $db)
    {
        $this->db = $db;
    }

    public function create(int $id,string $purpose): string
    {
        $args = [
            'token'         =>  bin2hex(random_bytes(64)),
            'id_user'       =>  $id,
            'expires_at'    =>  date('Y-m-d H:i:s', strtotime('+4 hours')),
            'purpose'       =>  $purpose,
        ];

        $sql = "INSERT INTO tokens (token, id_user, expires_at, purpose)
                VALUES (:token, :id_user, :expires_at, :purpose);";

        $this->db->executeSQL($sql, $args);
        return $args['token'];
    }

    public function validate(string $token, string $purpose): ?int
    {
        $args = [
            'token'     =>  $token,
            'purpose'   =>  $purpose
        ];

        $sql = "SELECT id_user FROM tokens 
               WHERE token = :token 
                 AND purpose = :purpose 
                 AND expires_at > NOW();";

        return $this->db->executeSQL($sql, $args)->fetchColumn();
    }
}