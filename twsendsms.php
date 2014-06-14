<?php
// Send SMS via twilio API
include("./twilio-php-master/Services/Twilio.php");
include("config.php");
include("functions.php");

$msg="Waiting...";

  $client = new Services_Twilio($accountsid, $authtoken);
  
  if( isset($_POST['Sender']) && isset($_POST['message']) ){
    $msg=$_POST['Sender'].$_POST['message'];
  }
  
  $sid = send_sms('+14088592715',$msg);

?>