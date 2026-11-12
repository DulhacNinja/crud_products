<?php

class Database
{
    # object storing conenction to database
    private $conn;

    public function __construct($host, $port, $dbname, $user, $pass){
        $dsn = "mysql:host={$host};port={$port};dbname={$dbname}";
        try {
            $this->conn = new PDO($dsn, $user, $pass, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
        }
        catch (PDOException $e){
            echo "Error : " . $e . "<br/>";
            die();
        }
    }
    public function query($query, $params): false|PDOStatement
    {

        $statement = $this->conn->prepare($query, $params);
        $statement->execute();
        return $statement;
    }

}