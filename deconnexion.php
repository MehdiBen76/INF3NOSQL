<?php
session_start();
$_SESSION = array();
session_destroy();
header("Location: testmembre_connection.php");
 ?>
