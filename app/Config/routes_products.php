<?

/* -= Роуты для филиалов (старая схема), не индексируются поисковиками =- */
Router::connect('/autozapchasti',
	array(
		'controller' => 'Products',
		'action' => 'index',
		'objectType' => 'Product',
	),
	array('named' => array('page' => 1))
);
Router::connect('/autozapchasti/:category',
	array(
		'controller' => 'Products',
		'action' => 'index',
		'objectType' => 'Product',
	),
	array('named' => array('category'))
);
Router::connect('/autozapchasti/page/:page',
	array(
		'controller' => 'Products',
		'action' => 'index',
		'objectType' => 'Product',
	),
	array('named' => array('page' => '[\d]*'))
);
Router::connect('/autozapchasti/:category/page/:page',
	array(
		'controller' => 'Products',
		'action' => 'index',
		'objectType' => 'Product',
	),
	array(
		'named' => array('category', 'page' => '[\d]*')
	)
);

Router::connect('/autozapchasti/:category/:subcategory',
	array(
		'controller' => 'Products',
		'action' => 'index',
		'objectType' => 'Product',
	),
	array('named' => array('category', 'subcategory'))
);
Router::connect('/autozapchasti/:category/:subcategory/page/:page',
	array(
		'controller' => 'Products',
		'action' => 'index',
		'objectType' => 'Product',
	),
	array(
		'named' => array('category', 'subcategory', 'page' => '[\d]*')
	)
);
Router::connect('/autozapchasti/:category/:subcategory/:slug',
	array(
		'controller' => 'Products',
		'action' => 'view',
		'objectType' => 'Product',
	),
	array('pass' => array('slug'))
);

/* -= Роуты для категорий-субдоменов (без категории в URL, новая схема), индексируется на субдоменах =- */
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