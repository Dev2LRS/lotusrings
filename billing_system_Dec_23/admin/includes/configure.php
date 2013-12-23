<?php
session_start();
error_reporting (E_ALL ^ E_NOTICE);
ini_set("display_errors","on");

//Connects to your Database
$config=mysql_connect("localhost", "billuser", "bill2012") or die(mysql_error());
if (!(mysql_select_db("billsysdb2012")))
{
	die("Unable to open database");
}
?>