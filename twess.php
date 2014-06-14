<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache'); // recommended to prevent caching of event data.

include("./include/dbconfig.php");

/**
 * Constructs the SSE data format and flushes that data to the client.
 *
 * @param string $id Timestamp/id of this connection.
 * @param string $msg Line of text that should be transmitted.
 */


  
function sendMsg($id, $msg) {
  echo "id: $id" . PHP_EOL;
  echo "data: $msg" . PHP_EOL;
  echo PHP_EOL;
  ob_flush();
  flush();
}


$serverTime = time();

mysql_connect($DBhostname, $DBuser, $DBpwd) or die("I couldn't connect to your database, please make sure your info is correct!");
mysql_select_db('twilio') or die("I couldn't find the database table ($table) make sure it's spelt right!");
  
$result = mysql_query("SELECT * FROM Msgs ORDER BY Timestamp DESC LIMIT 1;");

$row = mysql_fetch_array($result);
$date = date_create($row['Timestamp']);
$datestr=date_format($date, 'Y-m-d H:i:s');
$datenow=date('Y-m-d H:i:s',time());
$msgbody = $datenow."|".$datestr."|".$row['Msg'];



sendMsg($serverTime,$msgbody);