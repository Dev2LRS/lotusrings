<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Google Maps JavaScript API v3 Example: Directions Complex</title>
    <link href="/maps/documentation/javascript/examples/default.css" rel="stylesheet">
    <style>
      #directions-panel {
        height: 100%;
        float: right;
        width: 390px;
        overflow: auto;
      }

      #map-canvas {
        margin-right: 400px;
      }

      #control {
        background: #fff;
        padding: 5px;
        font-size: 14px;
        font-family: Arial;
        border: 1px solid #ccc;
        box-shadow: 0 2px 2px rgba(33, 33, 33, 0.4);
        display: none;
      }

      @media print {
        #map-canvas {
          height: 500px;
          margin: 0;
        }

        #directions-panel {
          float: none;
          width: auto;
        }
      }
    </style>
	<script type="text/javascript"  src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDgk8z6mLyIzEOfnOQUgqs-p_TwEMe-OHQ&sensor=true&libraries=places"></script>
    <script>
      var directionDisplay;
      var directionsService = new google.maps.DirectionsService();

      function initialize() {
        directionsDisplay = new google.maps.DirectionsRenderer();
        var mapOptions = {
          zoom: 7,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          center: new google.maps.LatLng(41.850033, -87.6500523)
        };
        var map = new google.maps.Map(document.getElementById('map_canvas'),
            mapOptions);
        directionsDisplay.setMap(map);
        directionsDisplay.setPanel(document.getElementById('directions-panel'));

        var control = document.getElementById('control');
        control.style.display = 'block';
        map.controls[google.maps.ControlPosition.TOP].push(control);
      }

      function calcRoute() {
        var start = document.getElementById('start').value;
        var end = document.getElementById('end').value;
        var request = {
          origin: start,
          destination: end,
          travelMode: google.maps.DirectionsTravelMode.DRIVING
        };
        directionsService.route(request, function(response, status) {
          if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);
          }
        });
      }

      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
  </head>
  <body>
    <div id="control">
      <strong>Start:</strong>
      <select id="start" onchange="calcRoute();">
        <option value="chicago, il">Chicago</option>
        <option value="st louis, mo">St Louis</option>
        <option value="joplin, mo">Joplin, MO</option>
        <option value="oklahoma city, ok">Oklahoma City</option>
        <option value="amarillo, tx">Amarillo</option>
        <option value="gallup, nm">Gallup, NM</option>
        <option value="flagstaff, az">Flagstaff, AZ</option>
        <option value="winona, az">Winona</option>
        <option value="kingman, az">Kingman</option>
        <option value="barstow, ca">Barstow</option>
        <option value="san bernardino, ca">San Bernardino</option>
        <option value="los angeles, ca">Los Angeles</option>
      </select>
      <strong>End:</strong>
      <select id="end" onchange="calcRoute();">
        <option value="chicago, il">Chicago</option>
        <option value="st louis, mo">St Louis</option>
        <option value="joplin, mo">Joplin, MO</option>
        <option value="oklahoma city, ok">Oklahoma City</option>
        <option value="amarillo, tx">Amarillo</option>
        <option value="gallup, nm">Gallup, NM</option>
        <option value="flagstaff, az">Flagstaff, AZ</option>
        <option value="winona, az">Winona</option>
        <option value="kingman, az">Kingman</option>
        <option value="barstow, ca">Barstow</option>
        <option value="san bernardino, ca">San Bernardino</option>
        <option value="los angeles, ca">Los Angeles</option>
      </select>
    </div>
    <div id="directions-panel"></div>
    <div id="map_canvas"></div>
  </body>
</html>