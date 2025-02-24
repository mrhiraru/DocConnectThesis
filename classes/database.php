<?php
class Database
{
    private $host = 'localhost';
    private $username = 'u701207055_docconnect_db';
    private $password = '#Docconnect_db32';
    private $database = 'u701207055_docconnect_db';
    protected $connection;

    /
    function connect()
    {
        try {
            $this->connection = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection error: " . $e->getMessage());
        }
        return $this->connection;
    }
}
?>