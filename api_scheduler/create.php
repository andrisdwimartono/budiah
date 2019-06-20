<?php
require_once('connection.php');

$query = mysqli_query($CON, "INSERT INTO test(`isi`) VALUES ('x')");
?>