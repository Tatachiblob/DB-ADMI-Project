<?php
session_start();
$_SESSION['isLogin'] = False;
session_destroy();
header("Location: login.php");
?>
