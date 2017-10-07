<?php

session_start();
if (isset($_SESSION['UserName'])){
    $pageTitle = 'Dashboard';
    include 'init.php';
    
     
     include $tpl . "footer.php";
    
}
else 
{
    header('Location:index.php');
    exit();
}