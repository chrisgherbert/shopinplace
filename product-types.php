<?php

$context = Timber::get_context();

$context['page_title'] = 'Business Types';

$context['terms'] = Timber::get_terms(
	[
		'taxonomy'  => 'product_type',
		'hide_empty' => true,
		'number' => 100
	]
);

Timber::render('term-list.twig', $context);