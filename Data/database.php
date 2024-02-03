
<?php
include "config.php";

$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>




<?php
class Database {
    public $host = DB_HOST;
    public $user = DB_USER;
    public $pass = DB_PASS;
    public $dbname = DB_NAME;
    public $link;
    public $error;

    public function __construct() {
        try {
            $this->connectDB();
        } catch (Exception $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    private function connectDB() {
        $this->link = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        if ($this->link->connect_error) { 
            throw new Exception("Connection fail: " . $this->link->connect_error);
        }
    }
    public function closeConnection() {
        if ($this->link) {
            $this->link->close();
        }
    }
    // Select or read data
    public function select($query) {
        $result = $this->link->query($query) or die($this->link->error.__LINE__);
        if ($result->num_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }

    // Insert data
    public function insert($query) {
        $insert_row = $this->link->query($query) or die($this->link->error.__LINE__);
        if ($insert_row) {
            return $insert_row;
        } else {
            return false;
        }
    }

    // Update data
    public function update($query) {
        $update_row = $this->link->query($query) or die($this->link->error.__LINE__);
        if ($update_row) {
            return $update_row;
        } else {
            return false;
        }
    }

    // Delete data
    public function delete($query) {
        $delete_row = $this->link->query($query) or die($this->link->error.__LINE__);
        if ($delete_row) {
            return $delete_row;
        } else {
            return false;
        }
    }
}
?>
