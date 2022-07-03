<?php 

session_start();
session_destroy();
header('location:s-user-login.php');
exit();

?>