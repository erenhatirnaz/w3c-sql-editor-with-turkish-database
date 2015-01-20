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
        $this->db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_COLUMN);
    }

    /**
     * @test
     */
    public function testIsCreatedDatabaseFile() {
        $this->assertTrue(file_exists('database.db') || file_exists('../database.db'));
    }

    /**
     * @test
     */
    public function testIsDatabaseCreatedSuccessfully(){
        $databaseTables = array('Calisanlar', 'KargoFirmalari', 'Kategoriler', 'Musteriler', 'Saticilar', 'Urunler', 'Siparisler', 'SiparisDetaylari');

        $getTableList = $this->db->prepare("SELECT name FROM sqlite_master WHERE type='table' AND name != 'sqlite_sequence'");
        $getTableList->execute();
        $tableNames = $getTableList->fetchAll();

        $this->assertEquals($databaseTables, $tableNames);
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
