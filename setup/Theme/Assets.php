<?php
/**
 * The class for enqueuing CSS & JS assets
 */

namespace Theme;

class Assets {

	public function __construct(){
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_javascript') );
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_stylesheets') );
	}

	public function enqueue_javascript(){

		// Create timestamp for cache-busting
		$timestamp = filemtime(get_template_directory() . '/dist/app.js');
		wp_register_script('main', get_template_directory_uri() . '/dist/app.js', ['jquery'], $timestamp, true);
		wp_localize_script('main', 'wpObject', [
			'ajaxUrl' => admin_url('admin-ajax.php'),
			'themeDir' => get_template_directory_uri(),
		]);
		wp_enqueue_script('main');

		// Search results map script
		wp_register_script('search-results-map', get_template_directory_uri() . '/assets/js/search-results-map.js', false, '', true);

	}

	public function enqueue_stylesheets(){

		// Create timestamp for cache-busting
		$timestamp = filemtime(get_template_directory() . '/dist/app.css');
		wp_enqueue_style('main', get_template_directory_uri() . '/dist/app.css', false, $timestamp);

		// Spartan MB Font
		wp_enqueue_style('spartan-mb', get_template_directory_uri() . '/assets/fonts/spartan-mb/stylesheet.css');

	}

}