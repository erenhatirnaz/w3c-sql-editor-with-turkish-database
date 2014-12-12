<?php
/**
 * Created by Atinasoft.
 * Project: w3c-sql-editor-with-turkish-database
 * User: Eren
 * Date: 12.12.2014
 * Time: 18:53
 * File: get-tables.php
 */

$db = new PDO("sqlite:database.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$getAllTables = $db->prepare("SELECT name FROM sqlite_master WHERE type='table'");
$getAllTables->execute();

$result = array();

foreach($getAllTables->fetchAll() as $fetch) {
    $tableName = $fetch["name"];
    if($tableName != "sqlite_sequence") {
        $tableDataCount = $db->query("SELECT COUNT(*) FROM " . $tableName)->fetchColumn();
        $result[$tableName] = $tableDataCount;
    }
}

echo json_encode($result);