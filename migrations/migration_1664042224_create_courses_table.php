<?php

use TimeDesign\Database\Database;
use TimeDesign\Migrator\Migration;

return new class extends Migration {

    /**
     * Runs the migration
     */
    public function up(Database $database) {
        $database->query("
            CREATE TABLE courses (
                id INTEGER PRIMARY KEY auto_increment,
                school_course_id VARCHAR(255) NOT NULL,
                name VARCHAR(255) NOT NULL,
                verified BOOLEAN NOT NULL DEFAULT false
            )
        ");
    }

    /**
     * Reverses the migration
     */
    public function down(Database $database) {
        $database->query("DROP TABLE courses");
    }
};
