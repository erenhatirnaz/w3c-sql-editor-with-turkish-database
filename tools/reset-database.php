<?php
/**
 * Created by Atinasoft.
 * Project: w3c-sql-editor-with-turkish-database
 * User: ErenHatirnaz
 * Date: 31.01.2015
 * Time: 20:41
 * File: reset-database.php
 */
define("ROOT_PATH", dirname(dirname(__FILE__)));
define("CURRENT_DIRECTORY", dirname(__FILE__));

header("Content-Type:text/plain; charset=utf8");

if (file_exists("database.db")) {
    copy(CURRENT_DIRECTORY . DIRECTORY_SEPARATOR . "database.db", ROOT_PATH . DIRECTORY_SEPARATOR . "database.db"); // copy tools/database.db to database.db
    echo "[+] Veritabanı başarılı bir şekilde sıfırlanmıştır.";
}
