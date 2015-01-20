<?php
/**
 * Created by Atinasoft.
 * Project: w3c-sql-editor-with-turkish-database
 * User: ErenHatirnaz
 * Date: 20.01.2015
 * Time: 02:40
 * File: genetator.php
 */

require_once("RandomDatabaseGenerator.class.php");

header("Content-Type:text/plain; charset=utf8;");

if (file_exists("database.db"))    { unlink("database.db"); }
if (file_exists("../database.db")) { unlink("../database.db"); }

$generator = new RandomDatabaseGenerator();
$generator->generateDatabase();