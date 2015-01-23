<?php
/**
 * Created by Atinasoft.
 * Project: w3c-sql-editor-with-turkish-database
 * User: ErenHatirnaz
 * Date: 20.01.2015
 * Time: 14:27
 * File: RandomDatabaseGeneratorTest.php
 */

require_once('../tools/RandomDatabaseGenerator.class.php');

class RandomDatabaseGeneratorTest extends PHPUnit_Framework_TestCase {
    private $generator;
    private $db;

    protected function setUp() {
        $this->deleteDatabaseFile();

        $this->generator = new RandomDatabaseGenerator();
        $this->generator->generateDatabase();

        $this->db = new PDO("sqlite:database.db");
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @test
     */
    public function testIsCreatedDatabaseFile() {
        $this->assertFileExists('database.db', "The database could not be created!");
    }

    /**
     * @test
     */
    public function testIsTablesCreatedSuccessfully() {
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_COLUMN);
        $databaseTables = array( 'Calisanlar', 'KargoFirmalari', 'Kategoriler', 'Musteriler', 'Saticilar', 'Urunler', 'Siparisler', 'SiparisDetaylari' );

        $getTableNames = $this->db->prepare("SELECT name FROM sqlite_sequence ORDER BY name");
        $getTableNames->execute();
        $tableNames = $getTableNames->fetchAll();

        $this->assertEmpty(array_merge(array_diff($databaseTables, $tableNames), array_diff($tableNames, $databaseTables)), "All tables are not created!");
    }

    /**
     * @test
     */
    public function testIsNotEmptyTables() {
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NAMED);

        $getTables = $this->db->prepare("SELECT * FROM sqlite_sequence");
        $getTables->execute();
        $tables = $getTables->fetchAll();

        foreach ($tables as $table) {
            $this->assertNotEquals(0, intval($table["seq"]), $table["name"] . " is empty!");
        }
    }

    private function deleteDatabaseFile() {
        if (file_exists("database.db")) {
            unlink("database.db");
        }
    }

    protected function tearDown() {
        unset($this->generator);
        unset($this->db);
        $this->deleteDatabaseFile();
    }
}
