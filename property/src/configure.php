<?php
// Connects to your Database
/*$config=mysql_connect("localhost", "root", "") or die(mysql_error());
if (!(mysql_select_db("map")))
{
	die("Unable to open database");
}*/
$config=mysql_connect("yuigooglemap.db.6877569.hostedresource.com", "yuigooglemap", "Gmap@2012") or die(mysql_error());
if (!(mysql_select_db("yuigooglemap")))
{
	die("Unable to open database");
}
?>
