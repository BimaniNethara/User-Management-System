<?php
$server_name="sql213.infinityfree.com";
$username="if0_42966883";
$password="Bimani2003";
$database="if0_42966883_user";

#Database Connection
$connection=new mysqli($server_name,$username,$password,$database);

if ($connection->connect_error) {
    die("Database connection failed");
}

$connection->set_charset("utf8mb4");

?>
