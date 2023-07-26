<!-- database.php -->
<?php
require_once 'user.php';

class Database {
    private $host;
    private $username;
    private $password;
    private $database;
    private $connection;
    private $user;



    public function __construct($host, $username, $password, $database) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->user = new User($this);
    }

    public function connect() {
        $this->connection = mysqli_connect($this->host, $this->username, $this->password, $this->database);
        if (!$this->connection) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public function getConnection() {
        return $this->connection;
    }

    public function getUser() {
        return new User($this);
        
    }
    public function prepare($query) {
        return $this->connection->prepare($query);
    }
}

?>