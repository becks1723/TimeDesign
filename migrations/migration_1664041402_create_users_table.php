<?php

use TimeDesign\Database\Database;
use TimeDesign\Migrator\Migration;

return new class extends Migration {

    /**
     * Runs the migration
     */
    public function up(Database $database) {
        $database->query("
            CREATE TABLE users (
                id INTEGER PRIMARY KEY auto_increment,
                rcs_id VARCHAR(255) NOT NULL UNIQUE,
                created_on DATETIME NOT NULL DEFAULT current_timestamp,
                is_admin BOOL NOT NULL DEFAULT false
            )
        ");
    }

    /**
     * Reverses the migration
     */
    public function down(Database $database) {
        $database->query("DROP TABLE users");
    }
};
