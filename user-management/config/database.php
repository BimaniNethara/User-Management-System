<?php
$server_name="localhost";
$username="root";
$password="";
$database="user";

#Database Connection
$connection=new mysqli($server_name,$username,$password,$database);

if ($connection->connect_error) {
    die("Database connection failed");
}

$connection->set_charset("utf8mb4");

?>