<?php
/**
 * Class to define our taxonomies
 *
 * Depends on the webdevstudios/taxonomy_core library
 */

namespace Theme;

class Taxonomies {

	protected $taxonomies = array(
		'neighborhood',
		'product_type',
		'fulfillment_type'
	);

	public function __construct(){

		if ( ! class_exists( 'Taxonomy_Core' ) ){
			error_log('Please load the webdevstudios/taxonomy_core library');
			return false;
		}

		if ($this->taxonomies && !empty($this->taxonomies)){
			foreach ($this->taxonomies as $taxonomy) {
				$this->$taxonomy();
			}
		}

	}

	////////////////
	// Taxonomies //
	////////////////

	public function neighborhood(){

		register_via_taxonomy_core(
			[
				'Neighborhood',
				'Neighborhoods',
				'neighborhood'
			],
			[
				'description' => 'Part of town that a business is located in',
				'hierarchical' => true,
				'show_ui' => true,
				'show_admin_column' => true,
				'query_var' => true
			],
			['business']
		);

	}

	public function product_type(){

		register_via_taxonomy_core(
			[
				'Product Type',
				'Product Types',
				'product_type'
			],
			[
				'description' => 'The sort of goods are services the business sells',
				'hierarchical' => true,
				'show_ui' => true,
				'show_admin_column' => true,
				'query_var' => true
			],
			['business']
		);

	}

	public function fulfillment_type(){

		register_via_taxonomy_core(
			[
				'Fulfillment Type',
				'Fulfillment Types',
				'fulfillment_type'
			],
			[
				'description' => 'How the business delivers products or services',
				'hierarchical' => true,
				'show_ui' => true,
				'show_admin_column' => true,
				'query_var' => true
			],
			['business']
		);

	}

}