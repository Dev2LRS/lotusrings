<?php
session_start();
error_reporting (E_ALL ^ E_NOTICE);
ini_set("display_errors","on");
// Connects to your Database

$config=mysql_connect("billsysdb2012.db.6877569.hostedresource.com", "billsysdb2012", "BSysDB@2012") or die(mysql_error());

if (!(mysql_select_db("billsysdb2012")))
{
	die("Unable to open database");
}


?>
