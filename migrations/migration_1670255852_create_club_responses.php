<?php

use TimeDesign\Database\Database;
use TimeDesign\Migrator\Migration;

return new class extends Migration {

    /**
     * Runs the migration
     */
    public function up(Database $database) {
        $database->query("
            CREATE TABLE club_responses (
                id INTEGER PRIMARY KEY auto_increment,
                club_id INTEGER NOT NULL,
                submitter_user_id INTEGER NOT NULL,
                role VARCHAR(255) NOT NULL,
                hours INTEGER NOT NULL,
                CONSTRAINT fk_club FOREIGN KEY (club_id)
                    REFERENCES clubs(id)
                    ON DELETE CASCADE,
                CONSTRAINT club_fk_user FOREIGN KEY (submitter_user_id)
                    REFERENCES users(id)
            )
        ");
    }

    /**
     * Reverses the migration
     */
    public function down(Database $database) {
        $database->query("DROP TABLE club_responses");
    }
};
