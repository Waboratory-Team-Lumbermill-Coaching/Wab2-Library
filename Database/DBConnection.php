<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 2/23/2022
 * Time: 5:14 PM
 */

namespace Database;

use MongoDB\Driver\Exception\ConnectionException;
use mysqli;

class DBConnection {
    private static $instance = null;
    private $dbConnection = null;
    private $host = null;
    private $dbname = null;

    private function __construct() {
        $db = parse_ini_file(__DIR__ . "\..\Config\db.ini");
        $this->host = 'localhost';
        $this->dbname = $db['dsn'];
        $username = $db['user'];
        $password = $db['pass'];
        $conn = new mysqli(
            $this->host,
            $username,
            $password,
            $this->dbname
        );

        if($conn->connect_error) {
            throw new ConnectionException();
        }

        $this->dbConnection = $conn;
    }

    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new DBConnection();
        }

        return self::$instance;
    }

    public function query($sqlCommands) {
        $sql = '';

        foreach($sqlCommands as $command => $args) {
            $sql .= $command . ' ' . $args . ' ';
        }

        $dbRequest = $this->dbConnection->query($sql);

        if(!$dbRequest) {
            return (object)[
                'error' => $this->dbConnection->error,
                'errors' => $this->dbConnection->error_list,
            ];
        }

        return $dbRequest;
    }

    public function escapeSpecialChars($value) {
        return $this->dbConnection->real_escape_string($value);
    }

    public function hasTable($tableName) {
        $sql = "
            SELECT * 
            FROM information_schema.tables
            WHERE table_schema = '" . $this->dbname . "' 
            AND table_name = '" . $tableName . "'
            LIMIT 1;
        ";

        $result = $this->dbConnection->query($sql);

        if($result) {
            return $this->dbConnection->query($sql)->num_rows > 0;
        }

        return false;
    }
}