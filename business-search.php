<?php

$context = Timber::get_context();

$context['page_title'] = 'Chicago Businesses';

$address = $_GET['address'] ?? false;
$distance = $_GET['distance'] ?? 5; // default to 5 mile radius

// Get Google Maps JS API key, to create the map on page
$context['google_maps_api_key'] = get_option('site_options')['GOOGLE_MAPS_JS_KEY'] ?? false;

if ($address){

	$context['search_form_data'] = [
		'address' => $address,
		'distance' => $distance,
	];

	$search = new Content\BusinessSearch();

	$search->set_address($address);
	$search->set_distance($distance);

	$context['search_data'] = $search->get_matches();

	if ($context['search_data']['businesses']) {

		$search_results_js_data = [
			'query_data' => [
				'address' => $address,
				'distance' => $distance,
				'coords' => $context['search_data']['search_coords'] ?? false
			],
			'results' => array_map(function($item) {

				return [
					'lat' => $item['business']->lat,
					'lng' => $item['business']->lng,
					'name' => $item['business']->title(),
					'marker_image' => get_template_directory_uri() . '/assets/img/map-marker.png',
					'link' => $item['business']->link(),
					'infowindow_content' => \Timber::compile('components/business-infowindow.twig',[
						'business' => $item['business']
					])
				];

			}, $context['search_data']['businesses'])
		];

	}

}
else {
	$search_results_js_data = false;
}

if (isset($search_results_js_data)) {

	add_action('wp_enqueue_scripts', function() use ($search_results_js_data) {

		wp_enqueue_script('search-results-map');

		if ($search_results_js_data) {
			wp_localize_script('search-results-map', 'searchResultsData', $search_results_js_data);
		}

	});

}

Timber::render('business-search.twig', $context);