<?php

$context = Timber::get_context();

$context['page_title'] = 'Neighborhoods';

$context['neighborhoods'] = Timber::get_terms(
	[
		'taxonomy'  => 'neighborhood',
		'hide_empty' => true,
		'number' => -1
	]
);


Timber::render('term-list.twig', $context);