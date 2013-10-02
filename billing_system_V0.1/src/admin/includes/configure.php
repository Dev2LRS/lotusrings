<?php
@session_start();
error_reporting (E_ALL ^ E_NOTICE);
ini_set("display_errors","off");
// Connects to your Database
/*$config=mysql_connect("localhost", "root", "") or die(mysql_error());
if (!(mysql_select_db("map")))
{
	die("Unable to open database");EDEDED
}*/
$config=mysql_connect("filemakershenll.db.6877569.hostedresource.com", "filemakershenll", "fileM@2012") or die(mysql_error());
if (!(mysql_select_db("filemakershenll")))
{
	die("Unable to open database");
}
?>
