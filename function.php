<?php

//getTitle v1.0

function getTitle()
{
    global $pageTitle;
    if(isset($pageTitle)) 
    {
        echo $pageTitle;
    }
    
    else 
    
    {
        echo 'Default';
    }
}


/*
** redirectHome V2.0
** Redirect Function [ This Function accept parameters ]
** $theMsg = Echo the Message
** $url = the link yow want to redirect to
** $seconds = seconds before redirecting 
*/
      
function redirectHome($theMsg, $url = null, $seconds = 3){
    
    if($url === null)
    {
        $url = 'dashboard.php';
        
    } 
    
       else
    {
        $url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ? $_SERVER['HTTP_REFERER'] : 'dashboard.php';
    }
    
    
    echo $theMsg;
    echo "<div class='alert alert-info'>You Will Be Redirected To $url After $seconds Seconds.</div>";
    
    header("refresh:$seconds;url=$url");
    exit();
    
}


/*
** Check items function v1.0
** function to check items in database [function accept parameters]
** $select = the item to select 
** $form = the table to select from
** $value = the value of select 
*/

function CheckItem($select, $from, $value){
    global $con;
    $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statement ->execute(array($value));
    $count = $statement->rowCount();
    return $count;
}


/*
** get latest records function v1.0
** function to get latest items from data base 
** $select = field to select 
** $table = the table to choose from 
** $limit = Number of records to get
*/

function getLatest($select, $table, $order, $limit=5){
    global $con;
    $getstmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    $getstmt->execute();
    $rows = $getstmt->fetchAll();
    return $rows;
}
