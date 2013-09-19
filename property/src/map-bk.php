<?php 
include_once "configure.php";
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
 <script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDR5H_NNlQCMWIVv2z--jWmKJjACobZNSM&sensor=true&libraries=places">
    </script>
    <script type="text/javascript">
	 var markerPoints = [];
	 var pointInfoString = [];
	var markers = [];
	var iterator = 0;
	var service;

	var propImage = 'market-farm.png';

	<?php
		@extract($_REQUEST);


		echo "var nearByLocation = new google.maps.LatLng(".$lat.",".$lng.");";
			$sql_branch=mysql_query("select * from  property");
			while($res_branch=mysql_fetch_array($sql_branch))
			{
		?>
			markerPoints.push(new google.maps.LatLng(<?php echo $res_branch['lat'] . "," .$res_branch['lan']; ?>));
			pointInfoString.push("<table width='200' align='center' border='1' cellpadding='5' cellspacing='0'><tr><td align='left' colspan='2'><b><?php echo $res_branch['name'] ?></b></td></tr><tr><td align='left'>Beds</td><td align='left'><?php echo $res_branch['beds'] ?></td></tr><tr><td align='left'>Baths</td><td align='left'><?php echo $res_branch['baths'] ?></td></tr><tr><td align='left'>&nbsp;</td><td align='left'><a onclick='alert(\"Details\")' style='cursor:pointer;color:blue;text-decoration:underline;'>Details</a></td></tr><tr><td align='left'>&nbsp;</td><td align='left'><input type='button' onclick='alert(\"Added to Favorites!\")' value='Favorites' name='btFav'></td></tr></table>");
		<?php }?>
		var map = "";
      function initialize() {

        var mapOptions = {
          center: new google.maps.LatLng(<?php echo $lat . ',' . $lng?>),
          zoom: 12,
          mapTypeId: google.maps.MapTypeId.SATELLITE
        };
		
        map = new google.maps.Map(document.getElementById("map_canvas"),  mapOptions);
      
		drop();
		
		
      }
		
	  function searchRequest(){
		  var searchValue = document.getElementById('search').value;
		   var request = {
			location: nearByLocation,
			radius: '1000',
			query : searchValue	
		  };

		service = new google.maps.places.PlacesService(map);
		service.textSearch(request, callback);
	  }
		
		var searchResultDisplayString = "";

		function callback(results, status) {
		  resetSearchResultStr();
		  if (status == google.maps.places.PlacesServiceStatus.OK) {
			for (var i = 0; i < results.length; i++) {
			  var place = results[i];
				//alert(results[i].formatted_address)
			  searchResultDisplayString += "<tr><td align='left'>"+(i+1)+"</td><td align='center'><img src='"+place.icon+"' border='0' /></td><td><b>"+place.name+"</b><br>"+place.formatted_address+"</td></tr>";
			  addSearchMarker(results[i]);
			}
			searchResultDisplayString += '</table>';
			if(results.length > 0)
				showResult();
			else
				showNoResult();
		  }else
			  showNoResult();
		  
		   resetSearchResultStr();
		}

	function resetSearchResultStr(){
		searchResultDisplayString = "<table width='100%' align='center' border='0' cellpadding='5' cellspacing='0' bgcolor='#3E3E3E'><tr><th>S.no</th><th>Icon</th><th>Address</th></tr>";
	}

	function showResult(){
		document.getElementById("searchResultContainer").innerHTML=searchResultDisplayString;
	}

	function showNoResult(){
		document.getElementById("searchResultContainer").innerHTML="<table width='100%' align='center' border='0' cellpadding='5' cellspacing='0' bgcolor='#3E3E3E'><tr><th>S.no</th><th>Icon</th><th>Address</th><tr><td colspan='3'>No Results Found</td></tr></table>";
	}

	function addSearchMarker(place) {

		var resultInfoString = "<table width='200' align='center' border='0' cellpadding='5' cellspacing='0'><tr><td align='left' colspan='2'><b>"+place.name+"</b></td></tr><tr><td align='left'>&nbsp;</td><td align='left'>"+place.formatted_address+"</td></tr></table>";

		var infowindow = new google.maps.InfoWindow({
            content: resultInfoString
        });
			

		var marker = new google.maps.Marker({
          position: place.geometry.location,
          map: map,
          draggable: false,
          animation: google.maps.Animation.DROP
        });
		 google.maps.event.addListener(marker, 'click', function() {
          infowindow.open(map,marker);
        });
      }

	  function drop() {
        for (var i = 0; i < markerPoints.length; i++) {
          setTimeout(function() {
            addMarker();
          }, i * 100);
        }
      }

	  function addMarker() {
		var infowindow = new google.maps.InfoWindow({
            content: pointInfoString[iterator]
        });
		var marker = new google.maps.Marker({
          position: markerPoints[iterator],
          map: map,
          draggable: false,
		  icon: propImage,
          animation: google.maps.Animation.DROP
        });
		 google.maps.event.addListener(marker, 'click', function() {
          infowindow.open(map,marker);
        });
        markers.push(marker);
		
        iterator++;
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
</head><body onLoad="initialize()">
 
<table width="1000" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" align="center">
  <tr>
    <td align="center" valign="top"><table width="100%" border="0" cellspacing="3" cellpadding="0" >
      <tr>
        <td align="center" height="100" valign="middle" style="border-bottom:1px solid #000;"><strong>This is header area</strong></td>
      </tr>
      <tr>
        <td align="center" valign="top"><table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr>
    <td align="center" width="40%" valign="top">
    <!--<div style="float:right; padding-right:10px; height:20px;"><a href="index.php?p=add" style="font-weight:bold; color:#000; text-decoration:none;">Add Property</a></div>-->
   <table width="100%" cellpadding="0" cellspacing="1"  bgcolor="#3E3E3E"  class="form">
	<tr><td colspan='2'><input type='text' style='width:600px' value='' id="search">&nbsp;&nbsp;<input type='button' value='Search' name='search' onclick='searchRequest();'> </td></tr>
	<tr>
	<td align="center" width="40%" valign="top">
	<div style="height:600px;overflow:auto;" id="searchResultContainer">
		<table width='100%' align='center' border='0' cellpadding='5' cellspacing='0' bgcolor='#3E3E3E'>
			<tr><th>S.no</th><th>Icon</th><th>Address</th></tr>
			<tr><td colspan="3">Search result comes here...</td></tr>
		</table>
	</div>
    </td>    
    <td width="100%" valign="top"><div id="map_canvas" style="width: 100%; height: 583px; float:left;"></td></tr>
</table></td>
      </tr><tr><td align="center" height="50" valign="middle" style="border-top:1px solid #000;"><strong>This is Footer Area</strong></td></tr></table></td></tr></table>
      </body></html>

