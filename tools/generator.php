<?php

/**
 * Created by Atinasoft.
 * Project: w3c-sql-editor-with-turkish-database
 * User: ErenHatirnaz
 * Date: 20.01.2015
 * Time: 02:40
 * File: genetator.php.
 */
define('ROOT_PATH', dirname(dirname(__FILE__)));
require_once ROOT_PATH.'/vendor/autoload.php';

header('Content-Type:text/plain; charset=utf8;');

if (file_exists('database.db')) {
    unlink('database.db');
}
if (file_exists(ROOT_PATH.DIRECTORY_SEPARATOR.'database.db')) {
    unlink(ROOT_PATH.DIRECTORY_SEPARATOR.'database.db');
}

$generator = new Tools\RandomDatabaseGenerator();
$generator->generateDatabase();
$generator->copyDatabaseFileTo(ROOT_PATH.DIRECTORY_SEPARATOR.'database.db');
