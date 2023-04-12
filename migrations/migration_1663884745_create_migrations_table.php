<?php

use TimeDesign\Database\Database;
use TimeDesign\Migrator\Migration;

return new class extends Migration {

    /**
     * Runs the migration
     */
    public function up(Database $database) {
        $database->query("
            CREATE TABLE migrations (
                id INTEGER NOT NULL AUTO_INCREMENT,
                name VARCHAR(255) NOT NULL,
                up_time TIMESTAMP NOT NULL DEFAULT current_timestamp,
                PRIMARY KEY(id)
            );
        ");
    }

    /**
     * Reverses the migration
     */
    public function down(Database $database) {
        $database->query("
            DROP TABLE migrations;
        ");
    }
};
