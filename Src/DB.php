<?php

declare(strict_types=1);


namespace App\Src;


use PDO;


/**
 * Database connection handler.
 * 
 */
final class DB
{
    private string $host;
    private string $dbname;
    private string $charset;
    private string $user;
    private string $password;


    /**
     * Initializes database connection parameters.
     *
     * Currently values are hardcoded. Consider loading them
     * from a configuration file or environment variables.
     * 
     */
    public function __construct()
    {
        $this->host = 'localhost';
        $this->dbname = 'app';
        $this->charset = 'utf8mb4';
        $this->user = 'root';
        $this->password = '';
    }

    
    /**
     * Returns a configured PDO instance.
     *
     * @return PDO PDO instance connected to the database.
     * 
     */
    public function pdo(): PDO
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";

        return new PDO($dsn, $this->user, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
    }
}