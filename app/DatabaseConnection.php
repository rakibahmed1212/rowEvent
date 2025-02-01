<?php

namespace App;

use mysqli;
use Exception;
use Dotenv\Dotenv;

class DatabaseConnection
{
    protected $host;
    protected $username;
    protected $password;
    protected $dbname;
    protected $conn;


    function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
        $this->host = $_ENV['DB_HOST'] ?: 'localhost';
        $this->username = $_ENV['DB_USER'] ?: 'root';
        $this->password = $_ENV['DB_PASS'] ?: '';
        $this->dbname = $_ENV['DB_NAME'] ?: 'testdb';
        $debugMode = $_ENV['APP_DEBUG'] ?? 'false';

        if ($debugMode === 'true') {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
            error_reporting(0);
        }

        $this->connect();
    }

    private function connect()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            throw new Exception("Connection failed: " . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8mb4");
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    }

    public function getConnection()
    {
        return $this->conn;
    }
}
