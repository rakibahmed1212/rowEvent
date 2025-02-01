<?php

namespace App;

use Exception;
use App\DatabaseConnection;

class ProcessDataContainer
{

    private $con;

    public function __construct()
    {
        $dbConnection = new DatabaseConnection();
        $this->con = $dbConnection->getConnection();
    }

    public function createData($table, $columns, $values)
    {
        $sql = "INSERT INTO $table $columns VALUES $values";
        $result = $this->con->query($sql);

        if ($result == true) {
            return $this->con->insert_id;
        } else {
            return "Error: " . $this->con->error;
        }
    }

    public function getSingleData($table, $where = '1=1')
    {
        $sql = "SELECT * FROM " . $table . " WHERE $where";
        $sql = $this->con->query($sql);

        $sql = $sql->fetch_assoc();
        return $sql;
    }

    public function getData($table, $conditions = '1=1', $select = '*')
    {
        $sql = "SELECT $select FROM $table WHERE $conditions";
        $result = $this->con->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateData($table, $updates, $conditions)
    {
        $sql = "UPDATE $table SET $updates WHERE $conditions";
        return $this->con->query($sql);
    }

    public function deleteData($table, $conditions)
    {
        $sql = "DELETE FROM $table WHERE $conditions";
        return $this->con->query($sql);
    }
}
