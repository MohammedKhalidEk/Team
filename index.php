<?php

session_start();
$noNavbar = '';
$pageTitle = 'Login';

if (isset($_SESSION['UserName'])){
     header('Location: dashboard.php');//redirect to dashboard page
}

include "init.php";
   
//check if user come from http post request
if ($_SERVER['REQUEST_METHOD']=='POST'){
    
    $UserName = $_POST['user'];
    $PassWord = $_POST['pass'];
    $hashedpass = sha1($PassWord);
    
//check if the user exist in database
    
    
    $stmt = $con->prepare("SELECT 
                                UserID, UserName, PassWord 
                          FROM 
                                users 
                          WHERE 
                                UserName = ? 
                          AND 
                                PassWord = ? 
                          AND 
                                GroupID = 1
                          LIMIT 1");
    
    
    $stmt->execute(array($UserName, $hashedpass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    
    if($count > 0 ){
        $_SESSION['UserName'] = $UserName;
        $_SESSION['ID'] = $row['UserID'];
        header('Location:dashboard.php');
        exit();
    }

}
?>


<form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
    
    <h4 class="text-center">Admin Login</h4>
    
    <input class="form-control" type="text" name="user" placeholder="UserName" autocomplete="off"/>
    
    <input class="form-control" type="password" name="pass" placeholder="PassWord" autocomplete="new-password"/>
    
    <input class="btn btn-primary btn-block" type="submit" value="Login"/>
    
</form>


<?php
    include $tpl . "footer.php"; 
?>
