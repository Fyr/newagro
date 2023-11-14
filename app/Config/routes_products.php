<?

/* -= Роуты для продуктов (старая схема) =- */
/*
Router::connect('/zapchasti',
	array('controller' => 'Products', 'action' => 'redirect404')
);
*/
Router::connect('/zapchasti',
	array('controller' => 'Pages', 'action' => 'redirect404')
);

Router::connect('/zapchasti/:category',
	array(
		'controller' => 'Products',
		'action' => 'index',
		'objectType' => 'Product',
	),
	array('named' => array('category'))
);
Router::connect('/zapchasti/page/:page',
	array(
		'controller' => 'Products',
		'action' => 'index',
		'objectType' => 'Product',
	),
	array('named' => array('page' => '[\d]*'))
);
Router::connect('/zapchasti/:category/page/:page',
	array(
		'controller' => 'Products',
		'action' => 'index',
		'objectType' => 'Product',
	),
	array(
		'named' => array('category', 'page' => '[\d]*')
	)
);

Router::connect('/zapchasti/:category/:subcategory',
	array(
		'controller' => 'Products',
		'action' => 'index',
		'objectType' => 'Product',
	),
	array('named' => array('category', 'subcategory'))
);
Router::connect('/zapchasti/:category/:subcategory/page/:page',
	array(
		'controller' => 'Products',
		'action' => 'index',
		'objectType' => 'Product',
	),
	array(
		'named' => array('category', 'subcategory', 'page' => '[\d]*')
	)
);
Router::connect('/zapchasti/:category/:subcategory/:slug',
	array(
		'controller' => 'Products',
		'action' => 'view',
		'objectType' => 'Product',
	),
	array('pass' => array('slug'))
);

/* -= Роуты для категорий-субдоменов (без категории в URL, новая схема), индексируется на субдоменах =- */
/*
Router::connect('/zapchasti',
	array(
		'controller' => 'Products',
		'action' => 'index',
		'objectType' => 'Product',
		'subdomain' => 1
	),
	array('named' => array('page' => 1))
);
Router::connect('/zapchasti/:subcategory',
	array(
		'controller' => 'Products',
		'action' => 'index',
		'objectType' => 'Product',
		'subdomain' => 1
	),
	array('named' => array('subcategory'))
);
Router::connect('/zapchasti/page/:page',
	array(
		'controller' => 'Products',
		'action' => 'index',
		'objectType' => 'Product',
		'subdomain' => 1
	),
	array('named' => array('page' => '[\d]*'))
);
Router::connect('/zapchasti/:subcategory/page/:page',
	array(
		'controller' => 'Products',
		'action' => 'index',
		'objectType' => 'Product',
		'subdomain' => 1
	),
	array(
		'named' => array('subcategory', 'page' => '[\d]*')
	)
);
Router::connect('/zapchasti/:subcategory/:slug',
	array(
		'controller' => 'Products',
		'action' => 'view',
		'objectType' => 'Product',
		'subdomain' => 1
	),
	array('pass' => array('slug'))
);

Router::connect('/cart',
	array(
		'controller' => 'Products',
		'action' => 'cart',
	)
);
*/