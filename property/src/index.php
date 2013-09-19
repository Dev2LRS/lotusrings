<?php 
include_once "configure.php";

if(isset($_REQUEST['property']))
{
	@extract($_REQUEST);
	mysql_query("INSERT INTO `property` (`name` ,`beds` ,`baths` ,`area` ,`price` ,`icontype` ,`iconvalue` ,`location` ,`street` ,`city` ,`country` ,`zipcode` ,`lan` ,`lat` ,`date`) VALUES ('$name', '$beds', '$baths', '$area', '$price', '$it', '$iv', '$location', '$street', '$city', '$country', '$zip', '$lan', '$lat', '".date("Y-m-d")."')");
	header("location:index.php");
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
<script type="text/javascript">
	
	var latitude = 33.930807;
	var longitude = -118.295181;
		
      function showMap() {		 
		document.location.href = 'map.php?lat='+latitude+'&lng='+longitude;
      }
</script>

    <style type="text/css">
<!--
.style1 {font-family: Arial, Helvetica, sans-serif;font-size: 12px}
.style3 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #333333;
}
.style6 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #333333; font-weight: bold; font-size: 14px}
-->
    </style>
</head><body >
 
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" align="center">
  <tr>
    <td align="center" valign="top"><table width="100%" border="0" cellspacing="3" cellpadding="0" >
      <tr>
        <td align="center" height="35" valign="middle" style="border-bottom:1px solid #000;"><strong>This is header area</strong></td>
      </tr>
      <tr style="height:1200px;">
        <td align="center" valign="top">
		  <table cellpadding="0" cellspacing="0" align="center" width="100%" class="form" border="0" bordercolor="red">
	<tr>
    <td align="center" style='width:300px' valign="top">
    <?php if($_REQUEST['p']=='add') {?>
    <form name="pro" method="post">
    <table width="100%" cellpadding="0" cellspacing="5">
    <tr><td align="left">Name</td><td align="left"><input type="text" name="name" id="name" /></td></tr>
    <tr><td align="left">Beds</td><td align="left"><input type="text" name="beds" id="beds" /></td></tr>
    <tr><td align="left">Baths</td><td align="left"><input type="text" name="baths" id="baths" /></td></tr>
    <tr><td align="left">Price</td><td align="left"><input type="text" name="price" id="price" /></td></tr>
    <tr><td align="left">Area</td><td align="left"><input type="text" name="area" id="area" /></td></tr>
    <tr><td align="left">Icon Type</td><td align="left"><input type="text" name="it" id="it" /></td></tr>
    <tr><td align="left">Icon Value</td><td align="left"><input type="text" name="iv" id="iv" /></td></tr>
    <tr><td align="left">Location</td><td align="left"><input type="text" name="location" id="location" /></td></tr>
    <tr><td align="left">Street</td><td align="left"><input type="text" name="street" id="street" /></td></tr>
    <tr><td align="left">City</td><td align="left"><input type="text" name="city" id="city" /></td></tr>
    <tr><td align="left">Country</td><td align="left"><input type="text" name="country" id="country" /></td></tr>
    <tr><td align="left">Zipcode</td><td align="left"><input type="text" name="zip" id="zip" /></td></tr>
    <tr><td align="left">Latitude</td><td align="left"><input type="text" name="lan" id="lan" /></td></tr>
    <tr><td align="left">Longitude</td><td align="left"><input type="text" name="lat" id="lat" /></td></tr>
    <tr><td align="left"></td><td align="left"><input type="submit" name="property" id="property" value="Submit" onclick="return validation();" /></td></tr>
    </table>
    </form>
    <?php } else { ?>

 <table cellpadding="0" cellspacing="0" align="center" width="100%" border="0" bordercolor="red">
    <tr><td align="left">
	   <div style="height:350px;overflow:auto;">
	   <table width="100%" cellpadding="0" cellspacing="1"  bgcolor="#3E3E3E">
		<tr><th>Pid</th><th>Address</th><th>Area</th><th>Price</th><th>Beds</th><th>Baths</th></tr>
		<?php  $i=1;
				$sql=mysql_query("select * from  property");
				while($res=mysql_fetch_array($sql))
				{
			?>
		<tr><td align="center"><?php echo $res['pid'];?></td>
			<td align="left"><?php echo $res['name'];?></td>
			<td align="left"><?php echo ($res['pid'] * 100); ?></td>
			<td align="left"><?php echo "$".($res['pid'] * $i); ?></td>
			<td align="center"><?php echo $res['beds'];?></td>
			<td align="center"><?php echo $res['baths'];?></td>
		</tr>
		<?php $i++; }?>
		</table>
		</div>		
	  </td>
   </tr>

<!-- Favorite of Properties displayed here -->
   <tr><td align="left">
	   <div style="height:350px;overflow:auto;">
	   <table width="100%" cellpadding="0" cellspacing="1"  bgcolor="#3E3E3E">
	    <tr><td align="center" colspan="7">Favorite Homes</td></tr>
		<tr><th>Fid</th><th>Pid</th><th>Address</th><th>Area</th><th>Price</th><th>Beds</th><th>Baths</th></tr>
		<?php
	            $i=1;
				$sql=mysql_query("select * from fav_property");
				while($res=mysql_fetch_array($sql))
				{
			?>
				<tr>
				    <td align="center"><?php echo $res['fid'];?></td>
					<td align="center"><?php echo $res['pid'];?></td>
					<td align="left"><?php echo $res['name'];?></td>
					<td align="left"><?php echo ($res['pid']*100); ?></td>
					<td align="left"><?php echo "$".($res['pid'] * $i); ?></td>
					<td align="center"><?php echo $res['beds'];?></td>
					<td align="center"><?php echo $res['baths'];?></td>
				</tr>
		  <?php  
			 $i++;	
			} ?>
		</table>
		</div>
	  </td>
   </tr>
<!-- Favorite of Places displayed here -->
 <tr><td align="left">
	   <div style="height:350px;overflow:auto;">
	   <table width="100%" cellpadding="0" cellspacing="1"  bgcolor="#3E3E3E">
	    <tr><td align="center" colspan="7">Favorite Places</td></tr>
		<tr><th>Fpid</th><th>Name</th><th>IconId</th><th>Address</th><th>Website</th></tr>
		<?php	          
				$sql=mysql_query("select * from fav_place");
				while($res=mysql_fetch_array($sql))
				{
			?>
				<tr>
				    <td align="center"><?php echo $res['fpid'];?></td>
					<td align="left"><?php echo $res['name'];?></td>
					<td align="center"><?php echo $res['iconid'];?></td>
					<td align="left"><?php echo ($res['address']*100); ?></td>
					<td align="left"><?php echo "$".($res['website'] * $i); ?></td> 
				</tr>
		  <?php  
			} ?>
		</table>
		</div>
	  </td>
   </tr>

 </table>
<?php 
 }
?>

</td>    
  <td align="center" style="padding-top: 20px;"><input type="button" name="btn_map" onclick="showMap();" value="Click here to view map"></td></tr> 
  </table>
</td>
      </tr><tr><td align="center" height="25" valign="middle" style="border-top:1px solid #000;"><strong>This is Footer Area</strong></td></tr></table></td></tr></table>

      </body></html>

