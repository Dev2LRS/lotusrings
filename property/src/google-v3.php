<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map_canvas { height: 100% }
    </style>
    <!-- <script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&sensor=SET_TO_TRUE_OR_FALSE"> -->
	  <script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDR5H_NNlQCMWIVv2z--jWmKJjACobZNSM&sensor=true">
    </script>
    <script type="text/javascript">
      function initialize() {
        var mapOptions = {
          center: new google.maps.LatLng(-34.397, 150.644),
          zoom: 8,
          mapTypeId: google.maps.MapTypeId.SATELLITE
        };
		
        var map = new google.maps.Map(document.getElementById("map_canvas"),
            mapOptions);

		// Creating a marker and positioning it on the map
		var marker = new google.maps.Marker({
		  position: new google.maps.LatLng(-34.397, 155.644), 
		  map: map
		});
      }
    </script>
  </head>
  <body onload="initialize()">
    <div id="map_canvas" style="width:90%; height:100%"></div>
  </body>
</html>