<?php
/**
 *  Template Name: Home Page
 */

$context = Timber::get_context();
$post = Timber::get_post($post->ID, 'Content\Post');

$context['post'] = $post;

// Get businesses
$context['businesses'] = Timber::get_posts([
	'post_type' => 'business',
	'posts_per_page' => -1,
	'orderby' => 'title',
	'order' => 'ASC'
], 'Content\Business');

// Get Google Maps JS API key, to create the map on page
$context['google_maps_api_key'] = get_option('site_options')['GOOGLE_MAPS_JS_KEY'] ?? false;

$search_results_js_data = [
	'query_data' => [
		'address' => 'chicago, il',
		'distance' => 20,
		'coords' => $context['search_data']['search_coords'] ?? false
	],
	'results' => array_map(function($item) {

		return [
			'lat' => $item->lat,
			'lng' => $item->lng,
			'name' => $item->title(),
			'marker_image' => get_template_directory_uri() . '/assets/img/map-marker.png',
			'link' => $item->link(),
			'infowindow_content' => \Timber::compile('components/business-infowindow.twig',[
				'business' => $item
			])
		];

	}, $context['businesses'])
];

add_action('wp_enqueue_scripts', function() use ($search_results_js_data) {

	wp_enqueue_script('search-results-map');

	if ($search_results_js_data) {
		wp_localize_script('search-results-map', 'searchResultsData', $search_results_js_data);
	}

});

Timber::render( array( 'page-' . $post->post_name . '.twig', 'page.twig' ), $context, false, TimberLoader::CACHE_NONE );
