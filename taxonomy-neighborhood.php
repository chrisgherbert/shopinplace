<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.2
 */

$context = Timber::get_context();

$context['page_overline'] = 'Neighborhood Businesses';
$context['page_title'] = single_term_title('', false);

$context['businesses'] = Timber::get_posts([
	'post_type' => 'business',
	'posts_per_page' => -1,
	'tax_query' => [
		[
			'taxonomy' => 'neighborhood',
			'field' => 'ID',
			'terms' => get_queried_object()->term_id
		]
	]
]);

$context['pagination'] = Timber::get_pagination();

// Get Google Maps JS API key, to create the map on page
$context['google_maps_api_key'] = get_option('site_options')['GOOGLE_MAPS_JS_KEY'] ?? false;

$search_results_js_data = [
	'query_data' => [
		'address' => 'chicago, il',
		'distance' => 20,
		'coords' => [
			'lat' => 41.8781136,
			'lng' => -87.6297982
		]
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

Timber::render( 'taxonomy-neighborhood.twig', $context );
