<?php 

$dbhost = "localhost";
$dbuser = "root";
$dbpasswd = "";
$dbname = "db_iclass";
$conn = mysqli_connect($dbhost, $dbuser, $dbpasswd, $dbname);

if (!$conn) { 
    die("Koneksi Gagal: " . mysqli_connect_error()); 
} 

?>