<?php
session_start();

session_unset(); s
session_destroy(); 

header("Location: ../index.php");
exit;
?>
