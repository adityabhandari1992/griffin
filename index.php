<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<meta charset="utf-8">
		<title>Group Route Finder</title>
		<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
		<script>
			var directionsDisplay;
			var directionsService = new google.maps.DirectionsService();
			var map;
			var stepDisplay;
			var markerArray = new Array();
			var starts = new Array();
			var index = 0;

			function initialize() {
				var chicago = new google.maps.LatLng(41.850033, -87.6500523);
				var mapOptions = {
					zoom:7,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					center: chicago
				}
				map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
			}
			
			function getMultipleRoute() {
				var end = document.getElementById("end").value;
			
				var requestarr=new Array();
				
				for (i=0;i<starts.length;i++) {
					requestarr.push({
						origin:starts[i],
						destination:end,
						travelMode: google.maps.DirectionsTravelMode.DRIVING
					});
				}
				
				var length = requestarr.length,
				request = null;
				directionsDisplayArr= new Array();
				var j=0;
				for (var i = 0; i < length; i++) {
					request = requestarr[i];
					directionsDisplayArr.push(new google.maps.DirectionsRenderer());
					directionsDisplayArr[i].setMap(map);
							
					directionsService.route(request, function(response, status) {
						if (status == google.maps.DirectionsStatus.OK) {
								directionsDisplayArr[j].setDirections(response);
								showSteps(response);
								j++;
						}
					});
				}
			}
			
			function showSteps(directionResult) {
				var myRoute = directionResult.routes[0].legs[0];

				for (var i = 0; i < myRoute.steps.length; i++) {
					var marker = new google.maps.Marker({
						position: myRoute.steps[i].start_point,
						map: map
					});
					attachInstructionText(marker, myRoute.steps[i].instructions);
					markerArray[i] = marker;
					}
			}

		function attachInstructionText(marker, text) {
			google.maps.event.addListener(marker, "click", function() {
				stepDisplay.setContent(text);
				stepDisplay.open(map, marker);
			});
		} 
		
		function submitform() {
			var inputdivs = document.getElementById("source-panel").children;
			for (i=0;i<inputdivs.length;i++) {
				starts [i] = inputdivs[i].children[0].value;
			}
			end = document.getElementById("end").value;
			console.log(starts);
			getMultipleRoute();
		}
		
		function addInput() {
			var newdiv = document.createElement("DIV");
			var newinput = document.createElement("INPUT");
			var inputcount = document.getElementById("source-panel").childElementCount;
			
			newinput.setAttribute("type", "text");
			newdiv.innerHTML = "Source"+(inputcount+1) + ": ";
			newdiv.appendChild(newinput);
			document.getElementById("source-panel").appendChild(newdiv);
		}
		
		function removeInput() {
			sp = document.getElementById("source-panel");
			if (sp.children.length>1)
				sp.removeChild(sp.lastElementChild);
		}
			
		google.maps.event.addDomListener(window, "load", initialize);
		</script>
	</head>
	<body>
		<div class="container">
			<div align="center">
				<h3>Group Route Finder</h3>
				<div id="source-panel">
					<div> Source1: <input type="text"> </div>
					<div> Source2: <input type="text"> </div>
				</div>
				<div id="destination-panel">
					Destination: <input type="text" name="end" id="end">
				</div>
				<p>
					<button type="button" class="btn btn-primary" onclick="addInput();">Add Source</button>
					<button type="button" class="btn btn-primary" onclick="removeInput();">Remove Source</button>
					<button type="button" class="btn btn-success" onclick="submitform();">Get routes</button>
				</p>
			</div>
			<div id="map-canvas" style="min-width: 500px; min-height: 400px;">Loading Google Map Api..</div>
		  </div>
	</body>
</html>
