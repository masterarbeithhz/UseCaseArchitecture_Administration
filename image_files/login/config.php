<?php
include('/envVariables.php');
session_start();
$con = mysqli_connect(DATABASENAME,DATABASEUSER,DATABASEPSWD,DATABASEDB);
// Check connection
if (!$con) {
 die("Connection failed: " . mysqli_connect_error());
}