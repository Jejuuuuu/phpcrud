<?php 
    session_start();
    $_SESSION['user'] = '';
    session_unset();
    header('location:login.php');