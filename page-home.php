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
	'order_by' => 'title',
	'order' => 'ASC'
]);

Timber::render( array( 'page-' . $post->post_name . '.twig', 'page.twig' ), $context, false, TimberLoader::CACHE_NONE );
