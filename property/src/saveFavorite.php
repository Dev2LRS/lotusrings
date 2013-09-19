<?php 
include_once "configure.php";

/*
http://shenll.net/property/saveFavorite.php?favType=place&name=ayyappan&iconid=2&address=jafferkhan&website=www.gogle.com&lan=43324324&lat=333333&types=typ5435&plcid=pl75&reference=ref43543&rating=rat454
http://shenll.net/property/saveFavorite.php?favType=property&pid=4
*/
if(isset($_REQUEST['favType']) && trim($_REQUEST['favType'])!="")
{ 
   //name,iconid,address,website,lan,lat
   @extract($_REQUEST);

   if($favType=="place")
   {
	   $Insrtsql="INSERT INTO `fav_place`(name,iconid,address,website,lan,lat,types,plcid,reference,rating,date) VALUES('".addslashes($name)."', '$iconid', '".addslashes($address)."','".addslashes($website)."','$lan','$lat','".addslashes($types)."','".addslashes($id)."','".addslashes($reference)."','".addslashes($rating)."',now())";
	   @mysql_query($Insrtsql);
   }
   else
   {
	   $resFav=mysql_query("select * from fav_property where pid=".$pid);
	   if(!(mysql_num_rows($resFav)>0))
	    {
			$res=mysql_query("select * from property where pid=".$pid);
			if(mysql_num_rows($res)>0)
			{
				while($row=mysql_fetch_array($res))
				{
					$Insrtsql="INSERT INTO `fav_property` (`pid`, `name`, `beds`, `baths`, `area`, `price`, `iconid`, `location`, `street`, `city`, `country`, `zipcode`, `lan`, `lat`, `date`) VALUES('$pid','".$row["name"]."','".$row["beds"]."','".$row["baths"]."','".$row["area"]."','".$row["price"]."','".$row["iconid"]."','".$row["location"]."','".$row["street"]."','".$row["city"]."','".$row["country"]."','".$row["zipcode"]."','".$row["lan"]."','".$row["lat"]."',now())";				
					@mysql_query($Insrtsql);
				}
			}
		}
   }
}

echo "yes";
?>