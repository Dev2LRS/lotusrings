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
 #directions-panel {
	height: 100%;
	float: right;
	width: 200px;
	overflow: auto;
  }
</style> 

<script type="text/javascript" src="js/jscript.js"></script>
<script type="text/javascript" src="js/jquery-1.2.6.min.js"></script>
<script type="text/javascript" src="js/lytebox_demo.js"></script>
<script type="text/javascript" src="js/open-popup.js"></script>
<link rel="stylesheet" type="text/css" href="js/lytebox.css" media="screen">

<script type="text/javascript"  src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDgk8z6mLyIzEOfnOQUgqs-p_TwEMe-OHQ&sensor=true&libraries=places"></script>
<script type="text/javascript" src="label.js"></script>
<script type="text/javascript">
	var markerPoints = [];
	var pointInfoString = [];
	var markers = [];
	var references = [];
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
	var homeInfoWindows = [];
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
	var currentInstance = 0;
	var resCountr=-1,Totresult, favCounter=0;
	var isInfoWindowOpened = false;

	var directionDisplay;
	
	var selectddPlc=null,selectedHome="";
	var countPlaces=0;
	var arrFavPlaceNames =[];
	var arrFavPlaceIconId =[];
	var arrFavPlaceLoc=[];

	var favPlaceIterator=0;
	var favPlacesearchMarkers=[];
	var pointFavPlaceInfoString=[];
	var favPlaceInfoWindows=[];
	var favPlacesearchInfoWindows=[];

	<?php
		@extract($_REQUEST);
		echo "nearByLocation = new google.maps.LatLng(".$lat.",".$lng.");";
			$sql_branch=mysql_query("select prop.*,fav.pid as favid from property prop left join fav_property fav on prop.pid=fav.pid");
			$count = 0;
			while($res_branch=mysql_fetch_array($sql_branch))
			{
			 $count++;
		?>
			markerPoints.push(new google.maps.LatLng(<?php echo $res_branch['lat'] . "," .$res_branch['lan']; ?>));
		  
			pointInfoString.push("<table width='100%' align='center' border='0' style='width:175px;margin:0px;' cellpadding='0' cellspacing='0'><tr><td align='left'>Area</td><td align='left'><?php echo ($res_branch['pid'] * 100) ?></td></tr><tr><td align='left'>Price</td><td align='left'>$<?php echo ($res_branch['pid'] * $count) ?></td></tr><tr><td align='left'>Beds</td><td align='left'><?php echo $res_branch['beds'] ?></td></tr><tr><td align='left'>Baths</td><td align='left'><?php echo $res_branch['baths'] ?></td></tr><tr><td align='left'>&nbsp;</td><td align='left'><a onclick='alert(\"Details\")' style='cursor:pointer;color:blue;text-decoration:underline;'>Details</a></td></tr><?php if($res_branch['favid']==""){?><tr><td align='left'><input type='button' id='btn<?php echo $res_branch['pid'];?>' value='Add to Favorites' onclick='addPropFav(<?php echo $res_branch['pid'] .','. ($count-1);?>)'></td><td>&nbsp;</td></tr><?php }?></table>");
		<?php }
	 
			$countPlaces=0;
			$sql_branch=mysql_query("select * from fav_place group by name");			
			while($res_place=mysql_fetch_array($sql_branch))
			{	
				 $res_place['name']=str_replace("'","`",stripslashes($res_place['name']));

				//$res_place['name']=str_replace("'","",stripslashes($res_place['name']));
				$res_place['address']=str_replace("'","",stripslashes($res_place['address']));
				$res_place['website']=str_replace("'","",stripslashes($res_place['website']));

				 if($res_place['website']!="")
				 {
					$resultInfoString = "<table width='200' align='center' border='0' cellpadding='5' cellspacing='0'><tr><td align='left'><b>".$res_place['name']."</b></td></tr><tr><td align='left'>".$res_place['address']."</td></tr><tr><td align='left'><a href='".$res_place['website']."'>".$res_place['website']."</a></td></tr><tr><td align='left' id='tdShowDistance_".$countPlaces."' style='display:none'>Distance:</td></tr><tr><td align='left'><input type='button' id='btnShowRoute_".$countPlaces."' value='Show Route' onclick=\\\"calcRoute(this,".$res_place['lat'].",".$res_place['lan'].",\\'".$res_place['name']."\\')\\\"></td></tr></table>";
				 }
				 else
				 {
					$resultInfoString = "<table width='200' align='center' border='0' cellpadding='5' cellspacing='0'><tr><td align='left'><b>".$res_place['name']."</b></td></tr><tr><td align='left'>".$res_place['address']."</td></tr><tr><td align='left' id='tdShowDistance_".$countPlaces."' style='display:none'>Distance:</td></tr><tr><td align='left'><input type='button' id='btnShowRoute_".$countPlaces."' value='Show Route' onclick=\\\"calcRoute(this,".$res_place['lat'].",".$res_place['lan'].",\\'".$res_place['name']."\\')\\\"></td></tr></table>";
				 }
		?>
			   arrFavPlaceNames.push("<?php echo $res_place['name'];?>");		  

			   arrFavPlaceIconId.push("<?php echo $res_place['iconid'];?>");

			   arrFavPlaceLoc.push(new google.maps.LatLng(<?php echo $res_place['lat'] . "," .$res_place['lan']; ?>));

			   pointFavPlaceInfoString.push("<?php echo $resultInfoString;?>");
		<?php
			   $countPlaces++;			
		    }
		?>		
		  countPlaces="<?php echo $countPlaces;?>";
		  function initialize(iconIds) {
			try
			  {				
					arriconIds=iconIds.split(',');
					for(icn=0;icn<arriconIds.length;icn++)
					{
					  MM_preloadImages('http://shenll.net/property/Display-Icon.php?iconid='+arriconIds[icn]);
					}
										
					var mapOptions={
					  center: new google.maps.LatLng(<?php echo $lat . "," . $lng;?>),
					  zoom: 10,
					  disableDoubleClickZoom:true,
					  mapTypeId: google.maps.MapTypeId.ROADMAP
					};
					
					map=new google.maps.Map(document.getElementById("map_canvas"),  mapOptions);
					google.maps.event.addListener(map, 'click', function(event) {			
					  if(!isInfoWindowOpened)
						 nearByLocation = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
					});

					drop();
					dropFavPlace();
			 }
			 catch(err)
			 {
			   //alert(err.message);
			 }
      }
		
	  function searchRequest(){

		var searchValue = document.getElementById('search').value;
		var radiusValue = document.getElementById('radius').value ;
		resCountr = -1;
		if(searchValue != "" && radiusValue != ""){
			if(selectedHome!="")
			{ 
				 var request = {
					location: nearByLocation,
					radius: (radiusValue * 1609.34),
					keyword : searchValue	
				  };

				service = new google.maps.places.PlacesService(map);
				service.search(request, callback);	
			}
			else
			{
			 alert("Please select property to search");
			}			  
		 }
	  }
		
		
		
		function callback(results, status) {

		  if (status == google.maps.places.PlacesServiceStatus.OK) {			

			//map.setCenter(map.getCenter());
			Totresult=results;
			for (var i = 0; i < results.length; i++) {
			    //var placeService = new google.maps.places.PlacesService(map);
				//placeService.getDetails(request, addSearchMarker);
			   new addSearchMarker(results[i], results[i].reference);
			}
			
		  }else
			  showNoResult();  
		  
		}
	
	function cleanupPrevResults(results) {

      try{

		for (var i = 0; i < references.length; i++){		  
		  if(searchMarkers[i] && (isFavPlace(references[i])==-1))
		   searchMarkers[i].setMap(null);
		}

		favCounter=0;

		prevResults="";
		searchInfoWindows = [];
		searchMarkers = [];
		references = [];
		}
		catch(err)
		{
		  alert(err.message);
		}
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

	function addSearchMarker(place, placeReference) {

		resCountr++;	
		if(resCountr == 0){
			resetSearchResultStr();
			if(prevResults != "")
			{
				cleanupPrevResults(prevResults);
			}			
		}		
		var favInstance = isFavPlace(place.name);
		references.push(place.name);
		if(favInstance == -1)
		{				
			var formatted_address = "";
			var website = "";
			var latlng = new google.maps.LatLng(place.geometry.location.lat(),place.geometry.location.lng());
			var resultInfoString = "";

			var infowindow = new google.maps.InfoWindow({
				disableAutoPan: true
			});

			searchInfoWindows.push(infowindow);			
			
			setTimeout(function (){new setPlaceDetails(placeReference)}, resCountr * 1000);

			google.maps.event.addListener(infowindow, 'closeclick', function(event) {
				isInfoWindowOpened = false;
			});		

			var letter = String.fromCharCode("A".charCodeAt(0) + resCountr);            
			var markerIcon = "http://maps.google.com/mapfiles/marker" + letter + ".png";
			var markerLoc = place.geometry.location;

			var marker = new google.maps.Marker({
				position: markerLoc,
				map: map,
				draggable: false,
				icon: markerIcon,
				animation: google.maps.Animation.DROP
			});

			google.maps.event.addListener(marker, 'click', function(event) {
				if(lastOpenedInfoWindow != "")
					lastOpenedInfoWindow.close();
					infowindow.open(map,marker);
					lastOpenedInfoWindow = infowindow;		  
					isInfoWindowOpened = true;
					disableMouseOut = true;
			});

			google.maps.event.addListener(marker, 'mouseover', function(event) {
				if(lastOpenedInfoWindow != "")
					lastOpenedInfoWindow.close();
					infowindow.open(map,marker);
					lastOpenedInfoWindow = infowindow;
					isInfoWindowOpened = false;
			});
			
			searchMarkers.push(marker);		
		}		
		else
		{
			searchInfoWindows.push(getFavInfoWindowInstance(place.name));	
			searchMarkers.push(getFavMarkerInstance(place.name));		
			markerIcon = "http://shenll.net/property/Display-Icon.php?iconid=" + arrFavPlaceIconId[favInstance];
		}
	
		var letter = String.fromCharCode("A".charCodeAt(0) + resCountr);      
		currentInstance = resCountr;

		searchResultDisplayString += "<tr><td align='left' style='cursor:pointer;' onclick='showInfoWindow("+resCountr+");'><img src='"+markerIcon+"' border='0' /></td><td align='left' onclick='showInfoWindow("+resCountr+");' style='cursor:pointer;color:blue;font-size:15px;'>"+place.name+"</td></tr>";

		if((Totresult.length - 1) == resCountr){
			showResult();
			searchResultDisplayString += '</table>';
			prevResults = Totresult;
		}
	}

	function isFavPlace(placeName){
		var favPlace = -1;
		for(var ref=0; ref<arrFavPlaceNames.length; ref++){
			if(arrFavPlaceNames[ref] == placeName){
				favPlace = ref;
			}
		}
		return favPlace;
	}

	function getInfoWindowInstance(reference){
		var instance;
		for(var ref=0; ref<references.length; ref++){
			if(references[ref] == reference){
				instance = ref;
			}
		}
		return searchInfoWindows[instance];
	}

	
 function getFavInfoWindowInstance(reference){
		var instance;
		for(var ref=0; ref<arrFavPlaceNames.length; ref++){
			if(arrFavPlaceNames[ref] == reference){
				instance = ref;
			}
		}
		return favPlacesearchInfoWindows[instance];
	}

	function getMarkerInstance(reference){
		var instance;
		for(var ref=0; ref<references.length; ref++){
			if(references[ref] == reference){
				instance = ref;
			}
		}
		return searchMarkers[instance];
	}

	function getFavMarkerInstance(reference){
		var instance;
		for(var ref=0; ref<arrFavPlaceNames.length; ref++){
			if(arrFavPlaceNames[ref] == reference){
				instance = ref;
			}
		}
		return favPlacesearchMarkers[instance];
	}


	function setPlaceDetails(referenceParam){
		var request = {
		  reference: referenceParam
		};
		var geocoderService = new google.maps.places.PlacesService(map);
		this.referenceParam = referenceParam;
		geocoderService.getDetails(request, function(result, state) {	
		  if (state == google.maps.places.PlacesServiceStatus.OK) {
				var name="";
				var formatted_address="";
				var website="";
				var types="";
				var id ="";
				var reference="";
				var rating="";
				var strname="";

				if(result.name)
			     {
				   name=result.name;
				   strname=name.replace(/'/g,"~~"); 
				 }

				if(result.formatted_address)
			    {
				  formatted_address=result.formatted_address;
				  formatted_address=formatted_address.replace(/'/g,"~~"); 
				}

				if(result.website)
				 website=result.website;

				if(result.types)
				 types=result.types;

				if(result.id)
				 id=result.id;				

				if(result.reference)
				 reference=result.reference;

				if(result.rating)
				 rating=result.rating;
				 
				 countPlaces++;			
				 if(website!="")
				 {					
						resultInfoString = "<table width='200' align='center' border='0' cellpadding='5' cellspacing='0'><tr><td align='left'><b>"+name+"</b></td></tr><tr><td align='left'>"+result.formatted_address+"</td></tr><tr><td align='left'><a href='"+website+"'>"+website+"</a></td></tr><tr><td align='left' id='tdShowDistance_"+countPlaces+"' style='display:none'>Distance:</td></tr><tr><td align='left'><input type='button' id=\"btnAddFavPlc_"+countPlaces+"\" value='Add to Favorites' onclick=\"initAddPlaceFav('"+strname+"','"+formatted_address+"','"+website+"','"+types+"','"+id+"','"+reference+"','"+rating+"','"+result.geometry.location.lng()+"','"+result.geometry.location.lat()+"','"+countPlaces+"')\"><input type='button' id=\"btnShowRoute_"+countPlaces+"\" value='Show Route' onclick=\"calcRoute(this,"+result.geometry.location.lat()+","+result.geometry.location.lng()+",'"+name+"')\" style=\"display:none\"></td></tr></table>";
				 }
				 else
				 {					
						resultInfoString = "<table width='200' align='center' border='0' cellpadding='5' cellspacing='0'><tr><td align='left'><b>"+name+"</b></td></tr><tr><td align='left'>"+result.formatted_address+"</td></tr><tr><td align='left' id='tdShowDistance_"+countPlaces+"' style='display:none'>Distance:</td></tr><tr><td align='left'><input type='button' id=\"btnAddFavPlc_"+countPlaces+"\" value='Add to Favorites' onclick=\"initAddPlaceFav('"+strname+"','"+formatted_address+"','"+website+"','"+types+"','"+id+"','"+reference+"','"+rating+"','"+result.geometry.location.lng()+"','"+result.geometry.location.lat()+"','"+countPlaces+"')\"><input type='button' id=\"btnShowRoute_"+countPlaces+"\" value='Show Route' onclick=\"calcRoute(this,"+result.geometry.location.lat()+","+result.geometry.location.lng()+",'"+name+"')\" style=\"display:none\"></td></tr></table>";
				 }

				var infoWindowInstance = getInfoWindowInstance(name);
				infoWindowInstance.setContent(resultInfoString);
			}else{
				alert(this.referenceParam);
			}
		});
}


function clearRoute(plcname){
    try
	{	
	  arrDirDisplayObj[plcname][3].setMap(null);
	  arrDirDisplayObj[plcname][3].setPanel(null);
      arrDirDisplayObj[plcname]=null;
	}
	catch(err)
	{
		//alert(err.message+" clearRoute");
	}
 }

var arrDirDisplayObj = new Object();
function calcRoute(btObj,endLat,endLng,plcname){
   
	var infoWindowInstance = getFavInfoWindowInstance(plcname);
	var strInfoContent = infoWindowInstance.getContent();
	var newInfoContent="";

    if(selectedHome!="")
	{ 
		if(btObj.value == "Show Route"){
			//clearRoute();
			
			newInfoContent = strInfoContent.replace("Show Route",'Hide Route');
			var directionsRendererOptions={suppressMarkers:true,preserveViewport:true};

			var directionsDisplay = new google.maps.DirectionsRenderer();
			directionsDisplay.setMap(map);
			
			//document.getElementById('td-directions-panel').style.display="";
			//directionsDisplay.setPanel(document.getElementById('directions-panel'));
			directionsDisplay.setOptions(directionsRendererOptions);
			
			var start = selectedHome;
			var end = new google.maps.LatLng(endLat,endLng);
			selectddPlc = end;

			var curPlcDet=[];
			curPlcDet[0]=btObj;
			curPlcDet[1]=endLat;
			curPlcDet[2]=endLng;
			curPlcDet[3]=directionsDisplay;
			arrDirDisplayObj[plcname]=curPlcDet;		

			var request = {
			  origin: start,
			  destination: end,
			  travelMode: google.maps.DirectionsTravelMode.DRIVING
			};
			var directionsService = new google.maps.DirectionsService();
			directionsService.route(request, function(response, status) {
			  if (status == google.maps.DirectionsStatus.OK) 
			   {
				  directionsDisplay.setDirections(response);

				   var arrRoutes=response.routes;
				   var plcDistance=0;
				   for(var rut=0;rut<arrRoutes.length;rut++)
				   {
					 var arrLegs=arrRoutes[rut].legs;
					  for(var lg=0;lg<arrLegs.length;lg++)
					  {
						plcDistance+=parseInt(arrLegs[lg].distance.value);
					  }
				   }

				   plcDistance=plcDistance*0.00062137119;
				    var arrPopupcnt1 = newInfoContent.split('Distance:');
				   var arrPopupcnt2 = newInfoContent.split('Miles');
				   if(arrPopupcnt2.length > 1)
						newInfoContent = arrPopupcnt1[0]+"Distance:"+arrPopupcnt2[1];

					newInfoContent = newInfoContent.replace("Distance:","Distance: "+plcDistance.toFixed(2)+" Miles");
				   newInfoContent = newInfoContent.replace("display:none","display:");
 				   infoWindowInstance.setContent(newInfoContent);
			   }
			});
			btObj.value = "Hide Route";

		}else{			

			selectddPlc = null;
			clearRoute(plcname);
			btObj.value = "Show Route";
			newInfoContent = strInfoContent.replace("Hide Route",'Show Route');		
			newInfoContent = newInfoContent.replace("display:","display:none");			
			var arrPopupcnt1 = newInfoContent.split('Distance:');
			var arrPopupcnt2 = newInfoContent.split('Miles');		    
			newInfoContent = arrPopupcnt1[0]+"Distance:"+arrPopupcnt2[1];			
			infoWindowInstance.setContent(newInfoContent);
		}		
	}
	else
	{
	 alert("Please select property to view route");
	}
}

function resetRoute(){

	  var arrCurPlcDet = new Object();	 
	  for(var disKey in arrDirDisplayObj){
       if (arrDirDisplayObj.hasOwnProperty(disKey)) 
		{		  
		   if(arrDirDisplayObj[disKey]!=null)
			{	
		      arrCurPlcDet[disKey]=arrDirDisplayObj[disKey];			  
			  clearRoute(disKey);	  
			}
		}
	  }	

	for(var disKey in arrCurPlcDet){
       if (arrCurPlcDet.hasOwnProperty(disKey)) 
		{		  		  
		   arrCurPlcDet[disKey][0].value = "Show Route";	
		  calcRoute(arrCurPlcDet[disKey][0],arrCurPlcDet[disKey][1],arrCurPlcDet[disKey][2],disKey);		  
		}
	  }
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

		homeInfoWindows.push(infowindow);

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

		  marker.setIcon(selIcon);	
		  if(lastSelectedMarker != "")
			  lastSelectedMarker.setIcon(defIcon);

		  if(lastOpenedInfoWindow != "")
			  lastOpenedInfoWindow.close();
          infowindow.open(map,marker);
		  lastOpenedInfoWindow = infowindow;			
		  	  
		  lastSelectedMarker = marker;		  
		  isInfoWindowOpened = true;
		  
		  nearByLocation = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
		  selectedHome = nearByLocation;
			searchRequest();
			resetRoute();

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
		
		/*
		google.maps.event.addListener(marker, 'mouseout', function() {
			infowindow.close();
		});*/

		var label = new Label({
		   map: map
		 });

		 label.text = iterator + 1;
		 label.bindTo('position', marker, 'position');
		 label.bindTo('text', marker, 'position');

        markers.push(marker);
		
        iterator++;
      }

function dropFavPlace() {
        for (var i = 0; i < arrFavPlaceNames.length; i++) {
            addFavPlaceMarker();
        }
      }

function addFavPlaceMarker()
{
	var infowindow = new google.maps.InfoWindow({
			disableAutoPan: true,
            content: pointFavPlaceInfoString[favPlaceIterator]
        });

		google.maps.event.addListener(infowindow, 'closeclick', function(event) {
			isInfoWindowOpened = false;
		});		

		var markerIcon = "http://shenll.net/property/Display-Icon.php?iconid=" + arrFavPlaceIconId[favPlaceIterator];	
	    markerLoc = arrFavPlaceLoc[favPlaceIterator];

		var marker = new google.maps.Marker({
			position: markerLoc,
			map: map,
			draggable: false,
			icon: markerIcon,
			animation: google.maps.Animation.DROP
		});

		google.maps.event.addListener(marker, 'click', function(event) {
			if(lastOpenedInfoWindow != "")
				lastOpenedInfoWindow.close();
				infowindow.open(map,marker);
				lastOpenedInfoWindow = infowindow;		  
				isInfoWindowOpened = true;
				disableMouseOut = true;
		});

		google.maps.event.addListener(marker, 'mouseover', function(event) {
			if(lastOpenedInfoWindow != "")
				lastOpenedInfoWindow.close();
				infowindow.open(map,marker);
				lastOpenedInfoWindow = infowindow;
				isInfoWindowOpened = false;
		});
		
		var label = new Label({
		   map: map
		 });

		 label.text = favPlaceIterator + 1;
		 label.bindTo('position', marker, 'position');
		 label.bindTo('text', marker, 'position');

		favPlacesearchMarkers.push(marker);   
		
		favPlacesearchInfoWindows.push(infowindow);   

		favPlaceIterator++;
}

function getHomeInfoWindowInstance(counter){
		return homeInfoWindows[counter];
	 }

	function MM_preloadImages() { //v3.0
	var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
	var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
	if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
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

<style>
  #fade { /*--Transparent background layer--*/
	display: none; /*--hidden by default--*/
	background: #000;
	position: fixed; left: 0; top: 0;
	width: 100%; height: 100%;
	opacity: .1;
	z-index: 9999;
}
.popup_block{
	display: none; /*--hidden by default--*/
	background: #fff;
	padding: 20px;
	border: 20px solid #ddd;
	float: left;
	font-size: 1.2em;
	position: fixed;
	top: 50%; left: 50%;
	z-index: 99999;
	/*--CSS3 Box Shadows--*/
	-webkit-box-shadow: 0px 0px 20px #000;
	-moz-box-shadow: 0px 0px 20px #000;
	box-shadow: 0px 0px 20px #000;
	/*--CSS3 Rounded Corners--*/
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border-radius: 10px;
}
img.btn_close {
	float: right;
	margin: -55px -55px 0 0;
}
/*--Making IE6 Understand Fixed Positioning--*/
*html #fade {
	position: absolute;
}
*html .popup_block {
	position: absolute;
}
</style>

</head>
<?php
$resIcn=mysql_query("SELECT * FROM `icon`");
$arrIcn=array();
while($rowIcn=mysql_fetch_array($resIcn))
{
  $arrIcn[]=$rowIcn["iconid"];
}
?>
 <body onLoad="initialize('<?php echo implode(",",$arrIcn);?>')">
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
	<td align="center" valign="top" style="width:200px;">
		<div style="height:1200px;overflow:auto;" id="searchResultContainer">
			<table width="100%" align='center' border='0' cellpadding='5' cellspacing='0' bgcolor='#3E3E3E'>
				<tr><th colspan="2">Search Results</th></tr>
				<tr><td colspan="2">No Results Found</td></tr>
			</table>
		</div>
    </td>    
    <td valign="top"><div id="map_canvas" style="width: 100%; height: 1200px; float:left;"></div></td>
	<td valign="top" style="width:200px;display:none;" id="td-directions-panel"><div id="directions-panel"></div></td>	
	</tr>
</table></td>
</tr>

<tr>
	<td align="center">
		<!--Icon select Popup display starts here -->
		<div id="popup_name" class="popup_block">	
            <form name="frmAddFav">
					<table align="center" border="0" width="30%" bordercolor="red" class="subpage-table" style="border:none">
						<tr style="height:5px"><td style="border:none"></td></tr>			
						<tr>
						 <td style="border:none;text-align:left" colspan="5"><b>Please select the Icon</b></td>
						 </tr>
						<tr>
						 <td align="center">
						   <table align="center" border="0">
							<?php
								$cellDvdr=1;
								$resIcn=mysql_query("SELECT * FROM `icon`");
								$TotIcncount=mysql_num_rows($resIcn);
								while($rowIcn=mysql_fetch_array($resIcn))
								{		
									if(($cellDvdr%10)==1)
									 echo "<tr>";

									$selct="";
									if($cellDvdr==1)
									 $selct="checked";
							?>				  
								   <td><input type="radio" name="rdIcon" id="rdIcon<?php echo $cellDvdr;?>" value="<?php echo $rowIcn["iconid"];?>" <?php echo $selct?> >&nbsp;</td>
								   <td align="left"><label for="rdIcon<?php echo $rowIcn["iconid"];?>"><img src="<?php echo "http://shenll.net/property/Display-Icon.php?iconid=".$rowIcn["iconid"];?>"></label>&nbsp;</td>				  
							 <?php
									if(($cellDvdr%10)==0 || $TotIcncount==$cellDvdr)
										 echo "</tr>";

									$cellDvdr++;
								}
							 ?>
							  </table>							  
							  <input type="hidden" name="hdnIcnCount" value="<?php echo $TotIcncount?>">
							 </td>
							</tr>
							<tr><td align='center'><label id="lblPlswait" style="display:none;"><img src="images/zoomloader.gif">&nbsp;Adding place to favorites. Please wait..</label><input type='button' id='btnIconadd' value='Add to Favorites' onclick="addPlaceFav()"></td></tr>
							<tr style="height:5px"><td style="border:none"></td></tr>
					</table>
			 </form>
		</div>
		<!--Icon select Popup display ends here -->
</td>
</tr>
<tr><td align="center" height="25" valign="middle" style="border-top:1px solid #000;"><strong>This is Footer Area</strong></td></tr></table></td></tr></table>
</body></html>