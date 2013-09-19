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
	font-family: Arial,sans-serif;
}
-->
</style> 
 <script type="text/javascript"  src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDR5H_NNlQCMWIVv2z--jWmKJjACobZNSM&sensor=true&libraries=places"></script>
 <script type="text/javascript" src="label.js"></script>
    <script type="text/javascript">
	 var markerPoints = [];
	 var pointInfoString = [];
	var markers = [];
	var searchMarkers = [];
	var iterator = 0;
	var service;
    var lastOpenedInfoWindow = "";
	var lastSelectedMarker = "";

	var propImage = 'home.png';
	var selectPropImage = 'home-red.png';
	var map = "";
	var searchResultDisplayString = "";
	var nearByLocation = "";
	var prevResults = "";
	var disableMouseOut =false;
	var selIcon = new google.maps.MarkerImage(selectPropImage,
		  // This marker is 20 pixels wide by 32 pixels tall.
		  new google.maps.Size(32, 32),
		  // The origin for this image is 0,0.
		  null,
		  // The anchor for this image is the base of the flagpole at 0,32.
		  null);
	var defIcon = new google.maps.MarkerImage(propImage,
		  // This marker is 20 pixels wide by 32 pixels tall.
		  new google.maps.Size(32, 32),
		  // The origin for this image is 0,0.
		  null,
		  // The anchor for this image is the base of the flagpole at 0,32.
		  null);
	var searchInfoWindows = [];
	
	var isInfoWindowOpened = false;

	<?php
		@extract($_REQUEST);


		echo "nearByLocation = new google.maps.LatLng(".$lat.",".$lng.");";
			$sql_branch=mysql_query("select * from  property");
			$count = 0;
			while($res_branch=mysql_fetch_array($sql_branch))
			{
			$count++;
		?>
			markerPoints.push(new google.maps.LatLng(<?php echo $res_branch['lat'] . "," .$res_branch['lan']; ?>));
			pointInfoString.push("<table width='100%' align='center' border='1' cellpadding='5' cellspacing='0'><tr><td align='left'>Area</td><td align='left'><?php echo ($res_branch['pid'] * 100) ?></td></tr><tr><td align='left'>Price</td><td align='left'>$<?php echo ($res_branch['pid'] * $count) ?></td></tr><tr><td align='left'>Beds</td><td align='left'><?php echo $res_branch['beds'] ?></td></tr><tr><td align='left'>Baths</td><td align='left'><?php echo $res_branch['baths'] ?></td></tr><tr><td align='left'>&nbsp;</td><td align='left'><a onclick='alert(\"Details\")' style='cursor:pointer;color:blue;text-decoration:underline;'>Details</a></td></tr><tr><td align='left'>&nbsp;</td><td align='left'><tr><td align='left'><input type='button' value='Add to Favorites' onclick='addPropFav(<?php echo $res_branch['pid'];'></td></tr></td></tr></table>");
		<?php }?>
		
      function initialize() {

        var mapOptions = {
          center: new google.maps.LatLng(<?php echo $lat . ',' . $lng?>),
          zoom: 10,
		  disableDoubleClickZoom:true,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
		
        map = new google.maps.Map(document.getElementById("map_canvas"),  mapOptions);
      
		 google.maps.event.addListener(map, 'click', function(event) {
			//alert(event.latLng.lat());
		  if(!isInfoWindowOpened)
			 nearByLocation = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
        });

		drop();
		
		
      }
		
	  function searchRequest(){
		  var searchValue = document.getElementById('search').value;
		  var radiusValue = document.getElementById('radius').value ;
		  
		if(searchValue != "" && radiusValue != ""){
			   var request = {
				location: nearByLocation,
				radius: (radiusValue * 1609.34),
				keyword : searchValue	
			  };

			service = new google.maps.places.PlacesService(map);
			service.search(request, callback);
		 }
	  }
		
		var currentInstance = 0;
		
		function callback(results, status) {
		  resetSearchResultStr();
		  if (status == google.maps.places.PlacesServiceStatus.OK) {

			if(prevResults != "")
				cleanupPrevResults(prevResults);

			//map.setCenter(map.getCenter());
			for (var i = 0; i < results.length; i++) {
			  var place = results[i];
				//alert(results[i].formatted_address)
			  var letter = String.fromCharCode("A".charCodeAt(0) + i);      
			  
			  var markerIcon = "http://maps.google.com/mapfiles/marker" + letter + ".png";			  

			  currentInstance = i;

			  searchResultDisplayString += "<tr><td align='left' style='cursor:pointer;' onclick='showInfoWindow("+i+");'><img src='"+markerIcon+"' border='0' /></td><td align='left' onclick='showInfoWindow("+i+");' style='cursor:pointer;color:blue;font-size:15px;'>"+place.name+"</td></tr>";
			  
			  var request = {
				  reference: place.reference
				};

				//var placeService = new google.maps.places.PlacesService(map);

				//placeService.getDetails(request, addSearchMarker);

				addSearchMarker(results[i], i)

			}
			searchResultDisplayString += '</table>';
			if(results.length > 0)
				showResult();
			else
				showNoResult();
		  }else
			  showNoResult();
		  
		   resetSearchResultStr();
		   prevResults = results;
		}
	
	function cleanupPrevResults(results) {
		for (var i = 0; i < results.length; i++) {
		  searchMarkers[i].setMap(null);
		}
		searchInfoWindows = [];
		searchMarkers = [];
	}

	function resetSearchResultStr(){
		searchResultDisplayString = "<table width='100%' align='center' border='0' cellpadding='5' cellspacing='0' bgcolor='#3E3E3E'><tr><th colspan='2'>Search Results</th></tr>";
	}

	function showResult(){
		document.getElementById("searchResultContainer").innerHTML=searchResultDisplayString;
	}

	function showNoResult(){
		document.getElementById("searchResultContainer").innerHTML="<table width='100%' align='center' border='0' cellpadding='5' cellspacing='0' bgcolor='#3E3E3E'><tr><th>Search Results</th><tr><td colspan='2'>No Results Found</td></tr></table>";
	}
	
	function showInfoWindow(instance){
		if(lastOpenedInfoWindow != "")
			  lastOpenedInfoWindow.close();
		searchInfoWindows[instance].open(map,searchMarkers[instance]);
		lastOpenedInfoWindow = searchInfoWindows[instance];
	}

	function formatAddress(address_components){
		var address = "";
		for(var comp = 0; comp < address_components.length; comp++){
			if(comp == 0)
				address = address_components[comp].long_name;
			else
				address += ", " + address_components[comp].long_name 
		}
	}

	var formatted_address = "";

	function addSearchMarker(place, i) {
		var formatted_address = "";
		var website = "";

		var latlng = new google.maps.LatLng(place.geometry.location.lat(),place.geometry.location.lng());
		var geocoderService = new google.maps.places.PlacesService(map);
		var request = {
		  reference: place.reference
		};

		geocoderService.getDetails(request, function(result, state) {
		  if (state == google.maps.places.PlacesServiceStatus.OK) {
				formatted_address = result.formatted_address;
				website = result.website;
			}		  
		});


		var resultInfoString = "<table width='200' align='center' border='0' cellpadding='5' cellspacing='0'><tr><td align='left'><b>"+place.name+"</b></td></tr><tr><td align='left'>"+formatted_address+"</td></tr><tr><td align='left'><a href='"+website+"'>"+website+"</a></td></tr><tr><td align='left'><input type='button' value='Add to Favorites' onclick='addPlaceFav("+place+","+formatted_address+","+website+");'></td></tr></table>";
	
		var infowindow = new google.maps.InfoWindow({
			disableAutoPan: true,
			content: resultInfoString
		});
		google.maps.event.addListener(infowindow, 'closeclick', function(event) {
			isInfoWindowOpened = false;
        });
		searchInfoWindows.push(infowindow);
		
		var letter = String.fromCharCode("A".charCodeAt(0) + i);            
		var markerIcon = "http://maps.google.com/mapfiles/marker" + letter + ".png";

		var marker = new google.maps.Marker({
		  position: place.geometry.location,
		  map: map,
		  draggable: false,
		  icon: markerIcon,
		  animation: google.maps.Animation.DROP
		});
		 
		 google.maps.event.addListener(marker, 'click', function() {
			nearByLocation = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
		  if(lastOpenedInfoWindow != "")
			  lastOpenedInfoWindow.close();
		  infowindow.open(map,marker);
		  lastOpenedInfoWindow = infowindow;		  
			isInfoWindowOpened = true;
			disableMouseOut = true;
		});
		
		 google.maps.event.addListener(marker, 'mouseover', function() {
		  if(lastOpenedInfoWindow != "")
			  lastOpenedInfoWindow.close();
		  infowindow.open(map,marker);
		  lastOpenedInfoWindow = infowindow;
		   isInfoWindowOpened = false;
		});

		google.maps.event.addListener(marker, 'mouseout', function() {
			infowindow.close();
		});

		var label = new Label({
		   map: map
		 });

		 label.text = i + 1;
		 label.bindTo('position', marker, 'position');
		 label.bindTo('text', marker, 'position');
		searchMarkers.push(marker);
      }



	  function drop() {
        for (var i = 0; i < markerPoints.length; i++) {
            addMarker();
        }
      }

	  function addMarker() {
		var infowindow = new google.maps.InfoWindow({
			disableAutoPan: true,
            content: pointInfoString[iterator]
        });

		google.maps.event.addListener(infowindow, 'closeclick', function(event) {
			isInfoWindowOpened = false;
        });

		if(lastSelectedMarker != "")
			markerImage = selectPropImage;
		else
			markerImage = propImage;

		var marker = new google.maps.Marker({
          position: markerPoints[iterator],
          map: map,
          draggable: false,
		  icon: propImage
        });
		 google.maps.event.addListener(marker, 'click', function(event) {
		  if(lastSelectedMarker != "")
			  lastSelectedMarker.setIcon(defIcon);
		  if(lastOpenedInfoWindow != "")
			  lastOpenedInfoWindow.close();
          infowindow.open(map,marker);
		  lastOpenedInfoWindow = infowindow;
			
		  marker.setIcon(selIcon);
		  
		  lastSelectedMarker = marker;		  
		  
			//alert(event.latLng.lat());
			isInfoWindowOpened = true;
		  
		  nearByLocation = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
			searchRequest();
        });

		 google.maps.event.addListener(marker, 'mouseover', function(event) {
		  
		  if(lastOpenedInfoWindow != "")
			  lastOpenedInfoWindow.close();
          infowindow.open(map,marker);
		  lastOpenedInfoWindow = infowindow;
		   isInfoWindowOpened = false;
		   disableMouseOut = false;
		  //nearByLocation = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
        });
		google.maps.event.addListener(marker, 'mouseout', function() {
			infowindow.close();
		});

		var label = new Label({
		   map: map
		 });

		 label.text = iterator + 1;
		 label.bindTo('position', marker, 'position');
		 label.bindTo('text', marker, 'position');

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
 
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" align="center">
  <tr>
    <td align="center" valign="top"><table width="100%" border="0" cellspacing="3" cellpadding="0" >
      <tr>
        <td align="center" height="35" valign="middle" style="border-bottom:1px solid #000;"><strong>This is header area</strong></td>
      </tr>
      <tr>
        <td align="center" valign="top"><table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr>
    <td align="center" width="25%" valign="top">
    <!--<div style="float:right; padding-right:10px; height:20px;"><a href="index.php?p=add" style="font-weight:bold; color:#000; text-decoration:none;">Add Property</a></div>-->
   <table width="100%" cellpadding="0" cellspacing="1"  bgcolor="#3E3E3E"  class="form">
	<tr><td colspan='2' align='left'><input type='text' style='width:500px' value='' id="search">&nbsp;&nbsp;<input type='button' value='Search' name='search' onclick='searchRequest();'>&nbsp;&nbsp;&nbsp; Radius <span style='color:gray'>(in Miles)</span> <input type='text' style='width:30px' value='2' id="radius">&nbsp;<span style='color:gray'> </td></tr>
	<tr>
	<td align="center" style='width:300px' valign="top">
	<div style="height:1200px;overflow:auto;" id="searchResultContainer">
		<table width="100%" align='center' border='0' cellpadding='5' cellspacing='0' bgcolor='#3E3E3E'>
			<tr><th colspan="2">Search Results</th></tr>
			<tr><td colspan="2">No Results Found</td></tr>
		</table>
	</div>
    </td>    
    <td valign="top"><div id="map_canvas" style="width: 100%; height: 1200px; float:left;"></td></tr>
</table></td>
      </tr><tr><td align="center" height="25" valign="middle" style="border-top:1px solid #000;"><strong>This is Footer Area</strong></td></tr></table></td></tr></table>
      </body></html>

