<?php

namespace TimeDesign\Migrator;

use TimeDesign\Core;

class Migrator {
    /** @var Migration[] $local_migrations */
    private $local_migrations = [];
    private $db_migrations = [];
    private $core;

    public function __construct(Core $core) {
        $this->loadLocalMigrations();
        $this->core = $core;
        $this->loadDatabaseMigrations();
    }

    private function loadDatabaseMigrations() {
        if (!$this->core->getDbQueries()->migrationTableExists()) {
            $key = array_key_first($this->local_migrations);
            $migration = $this->local_migrations[$key];
            $migration->up($this->core->getDb());
            $this->core->getDbQueries()->addMigration($key);
        }
        $this->db_migrations = $this->core->getDbQueries()->getMigrations();
    }

    private function loadLocalMigrations() {
        $migrations = scandir(__DIR__ . '/../../migrations');
        foreach ($migrations as $m) {
            if ($m === '.' || $m === '..') {
                continue;
            }
            $migration = require __DIR__ . '/../../migrations/' . $m;
            $this->local_migrations[$m] = $migration;
        }
        ksort($this->local_migrations);
    }

    private function getTemplateMigration(): string {
        $file = file_get_contents(__DIR__ . '/MigrationTemplate.php');
        if ($file === false) {
            throw new \Exception("Couldn't find migration template!");
        }
        return $file;
    }

    public function createMigration(string $name) {
        $time = time();
        $template = $this->getTemplateMigration();
        file_put_contents(__DIR__ . '/../../migrations/migration_' . $time . '_' . $name . '.php', $template);
    }

    public function migrate() {
        $local_names = array_keys($this->local_migrations);
        $db_names = [];
        foreach ($this->db_migrations as $m) {
            $db_names[] = $m['name'];
        }
        $missing = array_diff($local_names, $db_names);
        if (count($missing) === 0) {
            echo "Nothing to migrate." . PHP_EOL;
            return;
        }
        foreach ($missing as $m) {
            $this->local_migrations[$m]->up($this->core->getDb());
            $this->core->getDbQueries()->addMigration($m);
            echo "Migrated $m" . PHP_EOL;
        }
    }

    public function rollback() {
        $migrations = $this->core->getDbQueries()->getMigrations();
        $name = $migrations[array_key_last($migrations)]["name"];
        if (!key_exists($name, $this->local_migrations)) {
            echo "Migration not found locally, removing..." . PHP_EOL;
            $this->core->getDbQueries()->removeMigration($name);
            return;
        }
        $migration = $this->local_migrations[$name];
        $migration->down($this->core->getDb());
        $this->core->getDbQueries()->removeMigration($name);
        echo "Rollback complete." . PHP_EOL;
    }
}
