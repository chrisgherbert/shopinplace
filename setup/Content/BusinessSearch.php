<?php

namespace Content;

class BusinessSearch {

	public $address;
	public $distance = 10;

	public function set_address($address){
		$this->address = $address;
	}

	public function set_distance($distance){
		$this->distance = $distance;
	}

	public function get_matches(){

		// Get lat/lng for current search
		$coords = $this->get_geocoder_data($this->address);

		$results = $this->get_within_radius_of_point($coords['lat'], $coords['lng'], $this->distance, 1200);

		$business_ids = array_map(function($item){
			return $item->id;
		}, $results);

		// If there are no results within the range, give up.
		if (!$business_ids){
			return false;
		}

		$search_params = [
			'post_type' => 'business',
			'post_status' => 'publish',
			'post__in' => $business_ids,
			'orderby' => 'post__in',
			'posts_per_page' => -1
		];

		$business_objs = \Timber::get_posts($search_params);

		$businesses_with_distances = $this->pair_business_objects_with_distance($business_objs, $results);

		return [
			'search_coords' => $coords,
			'businesses' => $businesses_with_distances
		];

	}

	protected function pair_business_objects_with_distance($business_objs, $distance_query_results){

		$businesses_with_distances = [];

		foreach ($business_objs as $business){

			foreach ($distance_query_results as $row){

				if ($row->id == $business->ID){

					$businesses_with_distances[] = [
						'distance' => $row->distance,
						'business' => $business
					];

				}

			}

		}

		return $businesses_with_distances;

	}

	/**
	 * Gets geocoded address data using the Google Geocoder API
	 */
	protected function get_geocoder_data($address_string){

		// Get Google geocoder API key
		$api_key = get_option('site_options')['GOOGLE_MAPS_GEOCODER_KEY'] ?? false;

		if (!$api_key){
			throw new \Exception("Missing Google Geocoder API key");
			
		}

		$base_url = 'https://maps.googleapis.com/maps/api/geocode/json';

		$params = [
			'address' => $address_string,
			'key' => $api_key
		];

		$request_url = $base_url . '?' . http_build_query($params);

		$response = file_get_contents($request_url);

		if ($response){

			$response = json_decode($response, true);

			$latitude = $response['results'][0]['geometry']['location']['lat'] ?? false;
			$longitude = $response['results'][0]['geometry']['location']['lng'] ?? false;

			return [
				'lat'  => $latitude,
				'lng' => $longitude
			];

		}

	}

	protected static function get_within_radius_of_point($lat, $lng, $miles = 20, $limit = 100){

		global $wpdb;
		$posts_table = $wpdb->prefix . 'posts';
		$postmeta_table = $wpdb->prefix . 'postmeta';

		$query = "
			SELECT
				id, (
					3959 * acos (
					cos ( radians(%f) )
					* cos( radians( lat ) )
					* cos( radians( lng ) - radians(%f) )
					+ sin ( radians(%f) )
					* sin( radians( lat ) )
				)
			) AS distance
			FROM (
				SELECT ID id, m1.meta_value lat, m2.meta_value lng FROM $posts_table
				INNER JOIN $postmeta_table m1
					ON $posts_table.ID = m1.post_id
				INNER JOIN $postmeta_table m2
					ON $posts_table.ID = m2.post_id
				WHERE $posts_table.post_type = 'business'
				AND $posts_table.post_status = 'publish'
				AND ( m1.meta_key = 'lat')
				AND ( m2.meta_key = 'lng')
			) wp
			HAVING distance < %d
			ORDER BY distance
			LIMIT %d;
		";

		$prepared = $wpdb->prepare($query, $lat, $lng, $lat, $miles, $limit);

		$results = $wpdb->get_results($prepared);

		return $results;

	}

}
