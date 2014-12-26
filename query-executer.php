<?php
/**
 * Created by Atinasoft.
 * Project: w3c-sql-editor-with-turkish-database
 * User: ErenHatirnaz
 * Date: 12.12.2014
 * Time: 16:39
 * File: query-executer.php
 */


if($_POST) {
    $queryString = $_POST["queryString"];

    $db = new PDO("sqlite:database.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NAMED);

    try {
        $query = $db->prepare($queryString);
        $query->execute();

        $result = $query->fetchAll();

        echo json_encode($result);
    } catch (PDOException $e) {
        http_response_code(501);

        echo json_encode(array("error" => $e->getMessage()));
    }
}