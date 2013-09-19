<?php 
include_once "configure.php";

if(isset($_REQUEST['submit']))
{ 
	$imgtype=$_FILES['uploadfile']['type'];
	$name=$_REQUEST['name'];
	$image=$_FILES['uploadfile']['tmp_name'];

	$fp = fopen($image, 'r');
	$content = fread($fp, filesize($image));
	$content = addslashes($content);
	fclose($fp);

	$arrVal=array("1", "2", "3");
	$rand_keys = array_rand($arrVal,1);    
	$sql="insert into icon(type,value,icon,date) values ('".$arrVal[$rand_keys]."','1','$content',now())";
	$res=mysql_query($sql) or die (mysql_error());

	header("Location: Upload-Icon.php");
	exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Property</title>
<style type="text/css">
<!--
/* CSS Document */
body{
margin:0px auto;
background-color: #888888;
font-family:Arial, Helvetica, sans-serif;
font-size:11px;
}
.form th
{
	font-weight:bold;
	font-size:13px;
	background:#3E3E3E;
	color:#FFF;
	padding:5px;
}
.form td
{
	font-size:13px;
	background:#FFF;
	color:#000;
	padding:5px;
}
-->
</style> 
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" align="center">
<tr>
<td align="center" valign="top">	
<table width="100%" border="0" cellspacing="3" cellpadding="0" >
<tr>
<td align="center" height="35" valign="middle" style="border-bottom:1px solid #000;"><strong>This is header area</strong></td>
</tr>
<tr style="height:1200px;">
<td align="center" valign="top">
  <table cellpadding="0" cellspacing="0" align="center" width="100%" class="form" border="0" bordercolor="red">
		<tr>
		<td align="center" style='width:300px' valign="top">			 
			<form name="form" method="post" ENCTYPE="multipart/form-data" action="Upload-Icon.php">
				<table width="100%" cellpadding="0" cellspacing="5">				    
					<tr>
						<td align="left">
							Upload Icon: <input type="file" name="uploadfile">
						</td>
					</tr>
					<tr>
						<td><input name="submit" value="submit" type="submit"> </td>
					</tr>
				</table>
			</form> 
		</td>    
	   </tr> 
 </table>
</td>
</tr>
<tr><td align="center" height="25" valign="middle" style="border-top:1px solid #000;"><strong>This is Footer Area</strong></td></tr></table></td></tr></table>
</body>
</html>