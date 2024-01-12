<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tech";

$conn = mysqli_connect($servername, $username,$password,$dbname);
if($conn)
{
   // echo "Connection ok";
}
else{
    echo "connection failed";
}
?>
