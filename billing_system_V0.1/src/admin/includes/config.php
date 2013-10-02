<?php
session_start();
error_reporting (E_ALL ^ E_NOTICE);
ini_set("display_errors","on");
// Connects to your Database

if($_SERVER['SERVER_NAME'] == 'localhost')
{
    $config=mysql_connect("localhost", "root", "") or die(mysql_error());
}
 else 
{
   $config=mysql_connect("billsysdb2012.db.6877569.hostedresource.com", "billsysdb2012", "BSysDB@2012") or die(mysql_error()); 
}

if (!(mysql_select_db("billsysdb2012")))
{
	die("Unable to open database");
}
define('SITE_NAME',"Admin Control Panel");
define('RECORDS_PER_PAGE',"25");

?>
