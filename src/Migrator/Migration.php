<?php

namespace TimeDesign\Migrator;

use TimeDesign\Database\Database;

abstract class Migration {
    public abstract function up(Database $database);
    public abstract function down(Database $database);
}