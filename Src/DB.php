<?php

declare(strict_types=1);


namespace App\Src;

use \PDO;

final class DB
{
    private string $host;
    private string $dbname;
    private string $charset;
    private string $user;
    private string $password;


    // It would be better to download from the configuration file
    public function __construct()
    {
        $this->host = 'localhost';
        $this->dbname = 'app';
        $this->charset = 'utf8mb4';
        $this->user = 'root';
        $this->password = '';
    }

    public function pdo(): PDO
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";

        return new PDO($dsn, $this->user, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
    }
}