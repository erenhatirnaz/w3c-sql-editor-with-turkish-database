<?php
/**
 * Created by Atinasoft.
 * Project: w3c-sql-editor-with-turkish-database
 * User: ErenHatirnaz
 * Date: 12.12.2014
 * Time: 18:53
 * File: get-tables.php
 */

if (file_exists('database.db')) {
    $db = new PDO("sqlite:database.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $getAllTables = $db->prepare("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
    $getAllTables->execute();

    $result = array();

    foreach ($getAllTables->fetchAll() as $fetch) {
        $tableName = $fetch["name"];
        if ($tableName != "sqlite_sequence") {
            $tableDataCount = $db->query("SELECT COUNT(*) FROM " . $tableName)->fetchColumn();
            $result[$tableName] = $tableDataCount;
        }
    }

    echo json_encode($result);
} else {
    http_response_code(501);
    echo json_encode(array( 'error' => 'Database file not found!' ));
}