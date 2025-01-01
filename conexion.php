<?php
$host = "localhost";
$user = "root";
$password="";
$database = "ventas";
  $conn =mysqli_connect($host, %user,$password,$database);

if(!$conn){
die("conexion fallida:". mysqli_connect_error());
}
?>



