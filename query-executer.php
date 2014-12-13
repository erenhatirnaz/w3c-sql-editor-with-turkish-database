<?php
/**
 * Created by Atinasoft.
 * Project: w3c-sql-editor-with-turkish-database
 * User: Eren
 * Date: 12.12.2014
 * Time: 16:39
 * File: query-executer.php
 */

function is_not_numeric($var){
    return !is_numeric($var);
}

if($_POST) {
    $queryString = $_POST["queryString"];

    $db = new PDO("sqlite:database.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NAMED);

    $query = $db->prepare($queryString);
    $query->execute();

    //if($query->rowCount() > 0) {
        $result = $query->fetchAll();

        $columnNames = array_keys($result[0]);
        $columnNames = array( "columnNames" => array_filter($columnNames, "is_not_numeric") );

        $datas = array( "datas" => array_values($result) );

        echo json_encode(array_merge($columnNames, $datas));
    //}
}