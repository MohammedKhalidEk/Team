<?php
ob_start();
/*
============================================
== Manage Members Page
== you can add edit delete members from here
============================================
*/

session_start(); // Turn On Output Buffering
$pageTitle = 'Members';
 if (isset($_SESSION['UserName'])) // If There Is Any Session Opened
{
     include 'init.php';
        
     $do = isset($_GET['do']) ? $_GET['do'] : 'Manage'; // If Is Set Get Method  
    
    
    /*
     ** Here i will created an empty variable $query and if 
        Get Method == pending which mean that the user still 
        not activated and his RegStatus = 0 put an database 
        statement 'AND RegStatus = 0' and select all pending
        members from users table
     ** Then execute statement and fetch all data 
    */
    
    
 if($do == 'Manage')
{
    $query = '';
    if(isset($_GET['page']) && $_GET['page'] == 'pending') {
        
        $query = 'AND RegStatus = 0';
    }   
    
    $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query");
    $stmt->execute();
 //assign to variable 
    $rows = $stmt->fetchAll();
?>
    
<!---------------------------------------------------------------------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------------------------------------------------->

      <h1 class="text-center Dashboard">Manage Members</h1>
        <div class="container">
          <div class="table-responsive">
            <table class="main-table manage-members text-center table table-bordered">
                <tr>
                    <td>ID</td>
                   
                    <td>UserName</td>
                    <td>Email</td>
                    <td>Full Name</td>
                    <td>Registerd Date</td>                    
                    <td>Control</td>
                </tr>
                
                <?php
    
        foreach($rows as $row)
        {
                        echo "<tr>";
                            echo "<td>" . $row['UserID'] . "</td>";                            
                            echo "<td>" . $row['UserName'] . "</td>";
                            echo "<td>" . $row['Email'] . "</td>";
                            echo "<td>" . $row['FullName'] . "</td>";
                            echo "<td>" . $row['Date'] . "</td>";
            
            /*
            ** Here i will created three btns to edit or delete or activate user
            */
            
                            echo "<td>
                            <a href='members.php?do=Edit&UserID=" . $row['UserID'] ." ' class='btn btn-success btn-control'><i class= 'fa fa-edit'></i>Edit</a>      
                            
                            
                            <a href='members.php?do=Delete&UserID=" . $row['UserID'] . "' class='btn btn-danger confirm btn-delete'><i class= 'fa fa-close'></i>Delete</a>";
            
                            if($row['RegStatus'] == 0){
                                echo "<a href='members.php?do=Activate&UserID=" . $row['UserID'] . "' class='btn btn-info activate'><i class= 'fa fa-check'></i>Activate</a>";
                                
                                
                            }
                            echo  "</td>";    
                        echo "</tr>";
                        
        }
    
                ?>
                
          <tr>               
          </table>
      </div>
          <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Member</a>      
      </div>

             
<?php    }
    
    
/*------------------------------------------------------------------------------------------------------------------------------------------------
**Add*****************Add************Add**************Add******************Add*************Add********************Add************Add*********Add**
------------------------------------------------------------------------------------------------------------------------------------------------*/
    
    
    elseif($do== 'Add')
    {
?>


 <div class="container cont">
     <img src="layout/css/images/malecostume-512.png">
    <form  class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
        
        <div class="form-input form-group">    
          <div class="col-sm-10 col-md-12 col-lg-10">    
              <input type="text" name="UserName" class="form-control" autocomlete="off" required="required" placeholder="User Name"/> 
        </div>
        </div>
        
        
        
        <div class="form-input form-group">
           <div class="col-sm-10 col-md-12 col-lg-10">  
              <input type="password" name="password" class="form-control" autocomplete='new-password' required="required" placeholder="Password"/> 
        </div>
        </div>
        
        
            
        <div class="form-input form-group">
            <div class="col-sm-10 col-md-12 col-lg-10">  
              <input type="email" name="email" class="form-control" required="required" placeholder="Email Must Be Valid" />  
        </div>
        </div>
        
        
        <div class="form-input form-group">
            <div class="col-sm-10 col-md-12 col-lg-10"> 
            <input type="text" name="Full" class="form-control" required="required" placeholder="Full Name"/>    
        </div>
        </div>
        


        <div class="form-input form-group">
            <div class="col-sm-offset-0 col-sm-10">             
            <input type="submit" value="Add Member" class="btn btn-success btn-block" />    
        </div>
        </div>
     </form>
</div>

        
         


<!----------------------------------------------------------------------------------------------------------------------------------------------->
<!--Insert--------------------------Insert----------------Insert------------------Insert---------------------Insert------------------------Insert
<!----------------------------------------------------------------------------------------------------------------------------------------------->



<?php
        
    }
    elseif($do == 'Insert')
    {
    
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
        echo "<h1 class= 'text-center'> Add Member </h1>";
        echo "<div class='container'>";
                 
                
                //get variables from form
                  $user     = $_POST['UserName'];
                  $pass     = $_POST['password'];
                  $email    = $_POST['email'];
                  $name     = $_POST['Full'];
                  $hashpass = sha1($_POST['password']);
                
                //validate the form
                  $formErrors = array();
                
                if (strlen($user) < 6){
                    $formErrors[] = 'Username Can\'t Be Less Than <strong> 6 Characters</strong>';
                }
                
                 if (strlen($user) > 20){
                    $formErrors[] = 'Username Can\'t Be More Than <strong> 20 Characters</strong>';
                }
           
                 if (empty($user)){
                     $formErrors[] = 'UserName Cant Be <strong>Empty</strong>';
                 }
                
                
                  if (empty($pass)){
                     $formErrors[] = 'Password Cant Be <strong>Empty</strong>';
                 }
                
                
                  if (empty($email)){
                     $formErrors[] = 'Email Cant Be <strong>Empty</strong>';
                 }
                
                
                  if (empty($name)){
                     $formErrors[] = 'FullName Cant Be <strong>Empty</strong>';
                 }
                
               
                
                
                    foreach($formErrors as $error){
                        echo '<div class="alert alert-danger">' . $error . '</div>';
                       
                  }
                   
                //check if there is no errors proced the update operation 
                if (empty($formErrors)) {
                
               
                //check if user exist in database
                $check = checkItem("UserName", "users", $user);
                    if($check == 1){
                          $theMsg = "<div class='alert alert-danger'>"  . 'Sorry This UserName Is Exist</div>';
                        redirectHome($theMsg,'back');
                    }else {
                        
                    
                   
                //insert userinfo in database
                $stmt = $con->prepare("INSERT INTO 
                                      users(UserName, Password, Email, FullName, RegStatus, Date) 
                                      VALUES(:zuser, :zpass, :zmail, :zfullname, 1, now())");
                $stmt->execute(array(
                'zuser'         => $user,
                'zpass'         => $hashpass,
                'zmail'         => $email,    
                'zfullname'     => $name,
               
                ));
                    
                //Echo Success Message 
                        echo "<div class = 'container'>";
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';
                        redirectHome($theMsg,'back'); 
                        echo "</div>";
                    }
                    
            }
                
                }else
            {
                echo "<div class= 'container'>";
               $theMsg = '<div class="alert alert-danger">Sorry You Can\'t browse this page directly</div>';
                
               redirectHome($theMsg);    
                echo "</div>";
            }
        echo "</div>";
        
        
/*-----------------------------------------------------------------------------------------------------------------------------------------------
***Edit***************************Edit**************************Edit**********************************Edit**********************Edit*******Edit***
-----------------------------------------------------------------------------------------------------------------------------------------------*/
        
        
    }
    elseif($do == 'Edit')
    {    
        
    //check if get request userid is numeric & get the integer value of it 
        
    $userid = isset($_GET['UserID'])&&is_numeric($_GET['UserID'])?intval($_GET['UserID']):0;
        
    //select all data depend on this id  
        
      $stmt = $con->prepare("SELECT 
                                * 
                          FROM 
                                users 
                          WHERE 
                                UserID = ?
                          LIMIT 1");
    //execute data
        
    $stmt->execute(array($userid));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
        

        
if($count > 0){ ?>
    
    
        <div class="container cont">
            <img src="layout/css/images/malecostume-512.png">
            <form class="form-horizontal" action="?do=Update" method="POST">
            <input type="hidden" name="userid" value="<?php echo $userid ?>" />
                
                
            <div class="form-input form-group">
          <div class="col-sm-10 col-md-12 col-lg-10">    
            <input type="text" name="UserName" placeholder="UserName" value="<?php echo $row['UserName'] ?>" class="form-control" autocomlete="off" />    
        </div>    
        </div>

             <div class="form-input form-group">
          <div class="col-sm-10 col-md-12 col-lg-10">    
            <input type="hidden" placeholder="Password" name="oldpassword" value="<?php echo $row['password']?>"/>
            <input type="password" name="newpassword" class="form-control" autocomplete='new-password' />    
        </div>    
        </div>

            <div class="form-input form-group">
          <div class="col-sm-10 col-md-12 col-lg-10">    
            <input type="email" placeholder="Email" name="email" value="<?php echo $row['Email'] ?>" class="form-control"/>    
        </div>    
        </div>

            <div class="form-input form-group">
          <div class="col-sm-10 col-md-12 col-lg-10">    
            <input type="text" placeholder="Full Name" name="Full" value="<?php echo $row['FullName'] ?>" class="form-control"/>    
        </div>    
        </div>
                
    

            <div class="form-input form-group">
            <div class="col-sm-offset-0 col-sm-10">
            <input type="submit" value="Save" class="btn btn-success btn-block" />    
        </div>    
        </div>    
        </form>
        </div>


<!--==========================================================================================================================================--!>
<!--==========================================================================================================================================--!>


<?php
}
    
else
{
    echo "<div class = 'container'>";
    $theMsg = '<div class="alert alert-danger">There is no such ID</div>';
    redirectHome($theMsg);
    echo "</div>";
}
        
/*-----------------------------------------------------------------------------------------------------------------------------------------------
***Update***********************Update***************************Update************************Update**********************Update********Update***
------------------------------------------------------------------------------------------------------------------------------------------------*/
        
    }
    
    elseif ($do == 'Update')
    {
        echo "<h1 class= 'text-center  Dashboard'> Update Member </h1>";
        echo "<div class='container'>";
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                //get variables from form
                  $id       = $_POST['userid'];
                  $user     = $_POST['UserName'];
                  $email    = $_POST['email'];
                  $name     = $_POST['Full'];
                  
                //Pass trick
                  $pass='';
                  $pass = empty($_POST['newpassword']) ?  $_POST['oldpassword'] : sha1($_POST['newpassword']);
                
                //validate the form
                  $formErrors = array();
                
                if (strlen($user) < 6){
                    $formErrors[] = 'Username Can\'t Be Less Than <strong> 6 Characters</strong>';
                }
                 if (strlen($user) > 20){
                    $formErrors[] = '<div class="alert alert-danger">Username Can\'t Be More Than <strong> 20 Characters</strong>';
                }
           
                
                    foreach($formErrors as $error){
                        echo '<div class="alert alert-danger">' .  $error . '</div>';
                  }
                   
                
                //check if there is no errors proced the update operation 
                if (empty($formErrors)) {
                    
                     $stmt2 = $con->prepare("SELECT * FROM users WHERE UserName = ? AND UserID != ?");
                     $stmt2->execute(array($user, $id));
                     $count = $stmt2->rowCount();
                    
                     if ($count == 1){
                         echo '<div class="alert alert-danger">Sorry This UserName Is Exist</div>';
                         redirectHome($theMsg,'back');
                         
                     }else{
                     $stmt = $con->prepare("UPDATE users SET UserName =?, Email= ?, FullName =?, Password= ? WHERE UserID =?");
                     $stmt->execute(array($user, $email, $name, $pass, $id));
                
                //Echo Success Message 
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
                redirectHome($theMsg,'back');
            }
                }
                
                }else
            {
                
                echo "<div class = 'container'>";
                $theMsg = '<div class="alert alert-danger">Sorry You Can\'t browse this page directly</div>';
                redirectHome($theMsg);
                echo "</div>";
                
            }
        echo "</div>";
        
    /*-------------------------------------------------------------------------------------------------------------------------------------------
    **Delete******************************Delete****************************Delete**************************Delete***********************Delete**
    --------------------------------------------------------------------------------------------------------------------------------------------*/
        
    }
    elseif($do == 'Delete'){
        
        //Delete 
        echo "<h1 class= 'text-center Dashboard'> Delete Member </h1>";
        echo "<div class='container'>";
        
                
    $userid = isset($_GET['UserID'])&&is_numeric($_GET['UserID'])?intval($_GET['UserID']):0;
        
    //select all data depend on this id  
        
    $check = CheckItem('userid', 'users', $userid);        
        
    //execute data

if($check > 0)
{
    
    $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");
    $stmt->bindparam(":zuser", $userid);
    $stmt->execute();
       echo "<div class = 'container'>";
       $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Deleted</div>';
       redirectHome($theMsg, 'back');
       echo "</div>";
    
}
        else
        {
                echo "<div class = 'container'>";
                $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';
                redirectHome($theMsg);
                echo "</div>";
        }
        echo '</div>';
    }
    
    elseif($do='Activate'){
        
        echo "<h1 class= 'text-center Dashboard'> Activate Member </h1>";
        echo "<div class='container'>";
        
                
    $userid = isset($_GET['UserID'])&&is_numeric($_GET['UserID'])?intval($_GET['UserID']):0;
        
    //select all data depend on this id  
        
    $check = CheckItem('userid', 'users', $userid);        
        
    //execute data

if($check > 0)
{
    
    $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
    $stmt->execute(array($userid));
       echo "<div class = 'container'>";
       $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Updated</div>';
       redirectHome($theMsg);
       echo "</div>";
    
}
        else
        {
                echo "<div class = 'container'>";
                $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';
                redirectHome($theMsg);
                echo "</div>";
        }
        echo '</div>';
        
        
    }
    
    
/*================================================================================================================================================
*****************************************************         Footer         *********************************************************************
===============================================================================================================================================*/
    
    
     include $tpl . "footer.php";
    
}
else 
{
    header('Location:index.php');
    exit();
}

ob_end_flush();
?>