<?php

namespace TimeDesign\Database;

class MysqlDatabase extends Database {
    public function getDSN(string $dbname, string $host): string {
        return 'mysql:host=' . $host . ';dbname=' . $dbname;
    }

    public function query(string $query, array $params = []) {
        $this->statement = $this->connection->prepare($query);
        $this->statement->execute($params);
    }

    public function getPdoOptions(): array {
        return [];
    }

    public function getRows() {
        return $this->statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}
