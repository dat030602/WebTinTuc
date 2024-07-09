<?php
$server = 'localhost:3306';
$user = 'root';
$pass = 'dat123';
$database = 'publicationdb';

$conn = mysqli_connect($server, $user, $pass, $database);
if ($conn) {
    mysqLi_query($conn, "SET NAMES 'utf8' ");
} else {
    exit('Lỗi kết nối: ' . mysqli_connect_error());
}
?>