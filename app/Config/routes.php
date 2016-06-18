<?php
Router::parseExtensions('json', 'xml');
Router::connect('/', array('controller' => 'Pages', 'action' => 'home'));

/* -= Sitemap =- */
Router::connect('/news/sitemap',
	array(
		'controller' => 'sitemap',
		'action' => 'articles',
		'objectType' => 'News'
	)
);
Router::connect('/remont/sitemap',
	array(
		'controller' => 'sitemap',
		'action' => 'articles',
		'objectType' => 'RepairArticle'
	)
);
Router::connect('/offers/sitemap',
	array(
		'controller' => 'sitemap',
		'action' => 'articles',
		'objectType' => 'Offer'
	)
);
Router::connect('/brand/sitemap',
	array(
		'controller' => 'sitemap',
		'action' => 'articles',
		'objectType' => 'Brand'
	)
);
Router::connect('/motors/sitemap',
	array(
		'controller' => 'sitemap',
		'action' => 'articles',
		'objectType' => 'Motor'
	)
);
Router::connect('/magazini-zapchastei/sitemap',
	array(
		'controller' => 'sitemap',
		'action' => 'articles',
		'objectType' => 'Dealer'
	)
);
Router::connect('/articles/sitemap',
	array(
		'controller' => 'sitemap',
		'action' => 'articles',
		'objectType' => 'SectionArticle'
	)
);
Router::connect('/sitemap_plain',
	array(
		'controller' => 'sitemap',
		'action' => 'plain',
	)
);
Router::connect('/zapchasti/sitemap_:page.xml.gz',
	array(
		'controller' => 'sitemap',
		'action' => 'products',
	),
	array('pass' => array('page'))
);

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

/* -= Products =- */
Router::connect('/zapchasti',
	array(
		'controller' => 'Products',
		'action' => 'index',
		'objectType' => 'Product',
	),
	array('named' => array('page' => 1))
);
Router::connect('/zapchasti/:category',
	array(
		'controller' => 'Products',
		'action' => 'index',
		'objectType' => 'Product',
	),
	array('pass' => array('category'))
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
		'pass' => array('category'),
		'named' => array('page' => '[\d]*')
	)
);

Router::connect('/zapchasti/:category/:subcategory',
	array(
		'controller' => 'Products',
		'action' => 'index',
		'objectType' => 'Product',
	),
	array('pass' => array('category', 'subcategory'))
);

Router::connect('/zapchasti/:category/:subcategory/page/:page',
	array(
		'controller' => 'Products',
		'action' => 'index',
		'objectType' => 'Product',
	),
	array(
		'pass' => array('category', 'subcategory'),
		'named' => array('page' => '[\d]*')
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



CakePlugin::routes();

require CAKE.'Config'.DS.'routes.php';
