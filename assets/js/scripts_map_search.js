(function() {

	window.onload = function() {

		// Creating a new map
		var map = new google.maps.Map(document.getElementById("search_map"), {
          center: new google.maps.LatLng(45.438384, 10.991622),
          zoom: 13,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });


		// Creating the JSON data
		var json = script_data.markers;
//window.alert(json);
		// Creating a global infoWindow object that will be reused by all markers
		var infoWindow = new google.maps.InfoWindow();
		 var bounds = new google.maps.LatLngBounds();
		
		// Looping through the JSON data
		for (var i = 0, length = json.length; i < length; i++) {
			var data = json[i],
				latLng = new google.maps.LatLng(data.lat, data.lng);
bounds.extend( latLng);
			// Creating a marker and putting it on the map
			var marker = new google.maps.Marker({
				position: latLng,
				animation: google.maps.Animation.DROP,
				map: map,
				title: data.title
			});
			 
            
      

			// Creating a closure to retain the correct data, notice how I pass the current data in the loop into the closure (marker, data)
			(function(marker, data) {

				// Attaching a click event to the current marker
				google.maps.event.addListener(marker, "click", function(e) {
					
					 var contentString = '<div id="content">'+'<h1 id="firstHeading class="firstHeading">'+data.title +'</h1>'+
						'<div id="bodyImage">'+	data.img + '</div>' +				
						 '<div id="bodyContent">'+
											'<p>' + data.description +'</p>'+
											'<p><a href="' + data.link + '">'
											 + data.link + '</a> '+
											'</p>'+
											'</div>'+
											'</div>';
					
					infoWindow.setContent(contentString );
					infoWindow.open(map, marker);
				});


			})(marker, data);

		}
		 map.fitBounds(bounds);

	}

})();