<?php

use TimeDesign\Database\Database;
use TimeDesign\Migrator\Migration;

return new class extends Migration {

    /**
     * Runs the migration
     */
    public function up(Database $database) {
        $database->query("
            CREATE TABLE clubs (
                id INTEGER PRIMARY KEY auto_increment,
                name VARCHAR(255) NOT NULL,
                verified BOOLEAN NOT NULL DEFAULT false
            )
        ");
    }

    /**
     * Reverses the migration
     */
    public function down(Database $database) {
        $database->query("DROP TABLE clubs");
    }
};
