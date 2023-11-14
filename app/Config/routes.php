<?php
Router::parseExtensions('json', 'xml');
Router::connect('/', array('controller' => 'Pages', 'action' => 'home'));

require_once('routes_sitemap.php');
require_once('routes_products.php');
require_once('routes_filials.php');

/* -= News =- */
Router::connect('/news', array(
		'controller' => 'Articles', 
		'action' => 'index',
		'objectType' => 'News',
	),
	array('named' => array('page' => 1))
);
Router::connect('/news/:slug', 
	array(
		'controller' => 'Articles', 
		'action' => 'view',
		'objectType' => 'News'
	),
	array('pass' => array('slug'))
);
Router::connect('/news/page/:page', array(
	'controller' => 'Articles', 
	'action' => 'index',
	'objectType' => 'News'
),
	array('named' => array('page' => '[\d]*'))
);

/* -= Repair articles =- */
Router::connect('/remont', array(
	'controller' => 'Repair',
	'action' => 'index',
	//'objectType' => 'RepairArticle'
));
Router::connect('/remont/:slug',
	array(
		'controller' => 'Repair',
		'action' => 'index',
		//'objectType' => 'RepairArticle'
	),
	array('pass' => array('slug'))
);

/* -= Offers =- */
Router::connect('/offers', array(
		'controller' => 'Articles', 
		'action' => 'index',
		'objectType' => 'Offer',
	),
	array('named' => array('page' => 1))
);
Router::connect('/offers/:slug', 
	array(
		'controller' => 'Articles', 
		'action' => 'view',
		'objectType' => 'Offer'
	),
	array('pass' => array('slug'))
);
Router::connect('/offers/page/:page', array(
	'controller' => 'Articles', 
	'action' => 'index',
	'objectType' => 'Offer'
),
	array('named' => array('page' => '[\d]*'))
);

/* -= Brands =- */
Router::connect('/brand', array(
	'controller' => 'Articles',
	'action' => 'index',
	'objectType' => 'Brand',
),
	array('named' => array('page' => 1))
);
Router::connect('/brand/:slug',
	array(
		'controller' => 'Articles',
		'action' => 'view',
		'objectType' => 'Brand'
	),
	array('pass' => array('slug'))
);
Router::connect('/brand/page/:page', array(
	'controller' => 'Articles',
	'action' => 'index',
	'objectType' => 'Brand'
),
	array('named' => array('page' => '[\d]*'))
);

/* -= Motors =- */
Router::connect('/motors', array(
		'controller' => 'Articles', 
		'action' => 'index',
		'objectType' => 'Motor',
	),
	array('named' => array('page' => 1))
);
Router::connect('/motors/:slug', 
	array(
		'controller' => 'Articles', 
		'action' => 'view',
		'objectType' => 'Motor'
	),
	array('pass' => array('slug'))
);
Router::connect('/motors/page/:page', array(
	'controller' => 'Articles', 
	'action' => 'index',
	'objectType' => 'Motor'
),
	array('named' => array('page' => '[\d]*'))
);

/* -= Machine Tools =- */
Router::connect('/stanki', array(
	'controller' => 'Articles',
	'action' => 'index',
	'objectType' => 'MachineTool',
),
	array('named' => array('page' => 1))
);
Router::connect('/stanki/:slug',
	array(
		'controller' => 'Articles',
		'action' => 'view',
		'objectType' => 'MachineTool'
	),
	array('pass' => array('slug'))
);
Router::connect('/stanki/page/:page', array(
	'controller' => 'Articles',
	'action' => 'index',
	'objectType' => 'MachineTool'
),
	array('named' => array('page' => '[\d]*'))
);

/* -= Dealers =- */
Router::connect('/magazini-zapchastei', array(
		'controller' => 'Articles', 
		'action' => 'index',
		'objectType' => 'Dealer',
	),
	array('named' => array('page' => 1))
);
Router::connect('/magazini-zapchastei/:slug', 
	array(
		'controller' => 'Articles', 
		'action' => 'view',
		'objectType' => 'Dealer'
	),
	array('pass' => array('slug'))
);
Router::connect('/magazini-zapchastei/page/:page', array(
	'controller' => 'Articles', 
	'action' => 'index',
	'objectType' => 'Dealer'
),
	array('named' => array('page' => '[\d]*'))
);

/* -= Articles =- */
Router::connect('/articles', array(
	'controller' => 'Articles',
	'action' => 'index',
	'objectType' => 'SectionArticle',
),
	array('named' => array('page' => 1))
);
Router::connect('/articles/:slug',
	array(
		'controller' => 'Articles',
		'action' => 'view',
		'objectType' => 'SectionArticle'
	),
	array('pass' => array('slug'))
);
Router::connect('/articles/page/:page', array(
	'controller' => 'Articles',
	'action' => 'index',
	'objectType' => 'SectionArticle'
),
	array('named' => array('page' => '[\d]*'))
);

Router::connect('/catalog/:slug.pdf',
	array(
		'controller' => 'Catalog',
		'action' => 'viewPdf',
	),
	array('pass' => array('slug'))
);

CakePlugin::routes();

require CAKE.'Config'.DS.'routes.php';
