<?php

class DB {
    public $connection;
    public function __construct() {
        $db_host = "127.0.0.1";
        $db_name = "todo";
        $db_user = "todo";
        $db_pass = "todo_password123";
        $this->connection = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);;
    }

    public function __destruct() {
        $this->connection = null;
    }
}