<?php

include("./include/dbconfig.php");

mysql_connect($DBhostname, $DBuser, $DBpwd) or die("I couldn't connect to your database, please make sure your info is correct!");
mysql_select_db('twilio') or die("I couldn't find the database table ($table) make sure it's spelt right!");
 
if ( isset($_REQUEST['Body']) ) { 
     $msgbody = $_REQUEST['Body'];
   }  else {
     $msgbody = "NoBody";
   }
   
   $servertime=date('Y-m-d H:i:s',time());
   
      $sql = "INSERT INTO Msgs VALUES('$servertime','$msgbody')";
       
$add = mysql_query($sql);
mysql_close();
?>

