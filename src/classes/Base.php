<?php

class Base extends PDO
{
    public function __construct(string $dsn, ?string $username = null, ?string $password = null, ?array $options = [])
    {
        $default_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        $default_options[PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_ASSOC;
        $default_options[PDO::ATTR_EMULATE_PREPARES] = false;
        $options = array_replace($default_options, $options);
        parent::__construct($dsn, $username, $password, $options);
    }

    public function executeSQL(string $sql, ?array $args = null): PDOStatement
    {
        if (!$args) {
            return $this->query($sql);
        }
        
        $stmt = $this->prepare($sql);
        $stmt->execute($args);

        return $stmt;
    }
}