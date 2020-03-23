<?php
/**
 * This class creates our post types
 */

namespace Theme;

class PostTypes {

	protected $types = array(
		'business'
	);

	public function __construct(){
		if ($this->types && !empty($this->types)){
			foreach ($this->types as $type) {
				$this->$type();
			}
		}
	}

	/**
	 * Sample "article" content type - used in place of the default "post" content type
	 * to allow a custom rewrite base.  This makes it easier to track blog/article traffic
	 * specifically in analytics software.  However, it also can complicate the development
	 * process.  Proceed with caution.
	 */
	public function business(){

		register_via_cpt_core(
			[
				'Business',
				'Businesses',
				'business'
			]
			,[
				'menu_icon' => 'dashicons-store',
				'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
				'has_archive' => true
			]
		);

	}

}