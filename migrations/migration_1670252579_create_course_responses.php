<?php

use TimeDesign\Database\Database;
use TimeDesign\Migrator\Migration;

return new class extends Migration {

    /**
     * Runs the migration
     */
    public function up(Database $database) {
        $database->query("
            CREATE TABLE course_responses (
                id INTEGER PRIMARY KEY auto_increment,
                course_id INTEGER NOT NULL,
                submitter_user_id INTEGER NOT NULL,
                professor VARCHAR(255) NOT NULL,
                lecture_hours INTEGER NOT NULL,
                homework_hours INTEGER NOT NULL,
                study_hours INTEGER NOT NULL,
                difficulty INTEGER(1),
                comments TEXT,
                CONSTRAINT fk_course FOREIGN KEY (course_id)
                    REFERENCES courses(id)
                    ON DELETE CASCADE,
                CONSTRAINT course_fk_user FOREIGN KEY (submitter_user_id)
                    REFERENCES users(id)
            )
        ");
    }

    /**
     * Reverses the migration
     */
    public function down(Database $database) {
        $database->query("DROP TABLE course_responses");
    }
};
