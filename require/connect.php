<?php

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "sg";

    $mysqli  = new mysqli($servername, $username, $password, $database);

    if($mysqli->connect_error){
        die("connection failed:". $mysqli->connect_error);
        exit();   
    }
?>