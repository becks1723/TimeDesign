#!/usr/bin/env php
<?php

require_once __DIR__."/vendor/autoload.php";

use TimeDesign\Core;
use TimeDesign\Migrator\Migrator;

$core = new Core();

$migrator = new Migrator($core);

$long_options = [
    'migrate',
    'rollback',
    'create:'
];

$opt = getopt("", $long_options);

if (count($opt) === 0) {
    echo "Unknown option specified. Please try again" . PHP_EOL;
}

if (key_exists('migrate', $opt)) {
    // Run migrations
    $migrator->migrate();
}
else if (key_exists('rollback', $opt)) {
    // Rollback migrations
    $migrator->rollback();
}
else if (key_exists('create', $opt)) {
    // Create migration
    $migrator->createMigration($opt['create']);
}
