<?php

class DB
{
    private static ?DB $instance = null;
    private PDO $dbh;

    public static function getInstance(): DB
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->dbh;
    }

    private function __construct()
    {
        $this->dbh = new PDO('mysql:host=localhost;dbname=usersbook', 'Admin', '123456');
    }

}
