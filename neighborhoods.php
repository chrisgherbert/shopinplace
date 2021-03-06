<?php

$context = Timber::get_context();

$context['page_title'] = 'Neighborhoods';

$context['terms'] = Timber::get_terms(
	[
		'taxonomy'  => 'neighborhood',
		'hide_empty' => true,
		'number' => 100
	]
);


Timber::render('term-list.twig', $context);