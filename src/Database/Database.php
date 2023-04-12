<?php

namespace TimeDesign\Database;

use PDO;
use PDOStatement;

abstract class Database {
    protected PDO $connection;
    protected PDOStatement $statement;

    public function __construct(string $username, string $password, string $dbname, string $host) {
        $this->connect($username, $password, $dbname, $host);
    }

    public function connect(string $username, string $password, string $dbname, string $host) {
        $this->connection = new PDO($this->getDSN($dbname, $host), $username, $password, $this->getPdoOptions());
    }

    public abstract function getPdoOptions(): array;

    public abstract function getDSN(string $dbname, string $host): string;

    public abstract function query(string $query, array $params = []);

    public abstract function getRows();
}
