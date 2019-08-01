<?php
session_start();
unset($_SESSION["logged_in_teachers"]);
header("Location: login.php");
?>