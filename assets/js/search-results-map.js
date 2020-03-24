function initSearchResultsMap(){

	if (typeof searchResultsData !== 'undefined') {

		var centerCoords = {
			lat: searchResultsData.query_data.coords.lat,
			lng: searchResultsData.query_data.coords.lng
		}

	}
	else {

		// If no coordinates are provided, center the map on Chicago
		var centerCoords = {
			lat: 41.8781136,
			lng: -87.6297982
		}

	}

	var map = new google.maps.Map(document.getElementById('search-results-map'), {
		center: centerCoords,
		zoom: 7
	});

	if (typeof searchResultsData !== 'undefined') {

		var bounds = new google.maps.LatLngBounds();

		var prevInfowindow = false;

		searchResultsData.results.forEach(function(item){

			// Create marker and place on the map
			var marker = new google.maps.Marker({
				map: map,
				position: {
					lat: parseFloat(item.lat),
					lng: parseFloat(item.lng),
				},
				title: item.name,
				icon: item.marker_image
			});

			// Create "info window" for marker
			var infowindow = new google.maps.InfoWindow({
				content: item.infowindow_content
			 });

			marker.addListener('click', function() {

				if (prevInfowindow) {
					prevInfowindow.close();
				}

				prevInfowindow = infowindow;
				infowindow.open(map, marker);

			});

			// Add to bounds for map
			bounds.extend(marker.getPosition());

		});

		map.fitBounds(bounds);

	}

}