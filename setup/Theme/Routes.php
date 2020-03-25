<?php

namespace Theme;

class Routes {

	public function __construct(){

		\Routes::map('business-search', function($params){

			\Routes::load('business-search.php', null, null, 200);

		});

		\Routes::map('neighborhoods', function($params){

			\Routes::load('neighborhoods.php', null, null, 200);

		});

		\Routes::map('product-types', function($params){

			\Routes::load('product-types.php', null, null, 200);

		});

	}

}