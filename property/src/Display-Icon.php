<?php 
include_once "configure.php";

$gotten = @mysql_query("select icon from icon where iconid=".$_REQUEST['iconid']);
$row = @mysql_fetch_assoc($gotten);
$bytes = $row["icon"];

header("Content-type: image/jpeg");
print $bytes;
exit;
?>