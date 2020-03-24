<?php

namespace Theme\Hooks;

class ContentUpdate {

	public function __construct(){

		add_action('updated_post_meta', array($this, 'geocode_business_address'), 10, 4);
		add_action('added_post_meta', array($this, 'geocode_business_address'), 10, 4);

	}

	public function geocode_business_address($meta_id, $post_id, $meta_key, $meta_value){

		if ($meta_key === 'street_address' && get_post_type($post_id) === 'business'){

			// Get all address components
			$address = $meta_value;
			$city = get_post_meta($post_id, 'city', true);
			$state = get_post_meta($post_id, 'state', true);
			$zip = get_post_meta($post_id, 'zip_code', true);
			$title = get_the_title($post_id);

			// Get Google geocoder API key
			$api_key = get_option('site_options')['GOOGLE_MAPS_GEOCODER_KEY'] ?? false;

			// Make sure all address components are present
			if (!$address || !$city || !$state || !$zip){
				error_log('Missing address components - cannot geocode address.');
				return false;
			}

			// Make sure api key is present
			if (!$api_key){
				error_log('Missing Google Geocoder API key.');
				return false;
			}

			// Format address nicely for Google
			$address_string = "$title, $address, $city, $state $zip";

			// Get data from geocoder
			$geocoder_data = $this->get_geocoder_data($address_string);

			$latitude = $geocoder_data['results'][0]['geometry']['location']['lat'] ?? false;
			$longitude = $geocoder_data['results'][0]['geometry']['location']['lng'] ?? false;
			$location_type = $geocoder_data['results'][0]['types'][0] ?? false;

			// Set meta data for lat/long/type

			update_post_meta($post_id, 'lat', $latitude);
			update_post_meta($post_id, 'lng', $longitude);
			update_post_meta($post_id, 'location_type', $location_type);

		}

	}

	/**
	 * Gets geocoded address data using the Google Geocoder API
	 */
	protected function get_geocoder_data($address_string){

		// Get Google geocoder API key
		$api_key = get_option('site_options')['GOOGLE_MAPS_GEOCODER_KEY'] ?? false;

		if (!$api_key){
			error_log('Missing Google Geocoder API key!');
			return false;
		}

		$base_url = 'https://maps.googleapis.com/maps/api/geocode/json';

		$params = [
			'address' => $address_string,
			'key' => $api_key
		];

		$request_url = $base_url . '?' . http_build_query($params);

		$response = file_get_contents($request_url);

		if ($response){
			return json_decode($response, true);
		}

	}

}