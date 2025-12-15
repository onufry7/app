<?php

declare(strict_types=1);


namespace App\Src;

use \PDO;

class Controller
{
    protected PDO $pdo;

    public function __construct()
    {
        $db = new DB();
        $this->pdo = $db->pdo();
    }
}