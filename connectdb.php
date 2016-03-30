<?php
$mysqli = new mysqli("localhost", "root","", "neighbor_network");
if ($mysqli)
printf("\n connected"); 
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
?>
