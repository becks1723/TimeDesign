<?php

use TimeDesign\Database\Database;
use TimeDesign\Migrator\Migration;

return new class extends Migration {

    /**
     * Runs the migration
     */
    public function up(Database $database) {
        $database->query("
            CREATE TABLE job_responses (
                id INTEGER PRIMARY KEY auto_increment,
                job_id INTEGER NOT NULL,
                submitter_user_id INTEGER NOT NULL,
                job_type INTEGER(1) NOT NULL,
                hours INTEGER NOT NULL,
                CONSTRAINT fk_job FOREIGN KEY (job_id)
                    REFERENCES jobs(id)
                    ON DELETE CASCADE,
                CONSTRAINT job_fk_user FOREIGN KEY (submitter_user_id)
                    REFERENCES users(id)
            )
        ");
    }

    /**
     * Reverses the migration
     */
    public function down(Database $database) {
        $database->query("DROP TABLE job_responses");
    }
};
