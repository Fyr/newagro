<?php
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
Router::connect('/stanki/sitemap',
	array(
		'controller' => 'sitemap',
		'action' => 'articles',
		'objectType' => 'MachineTool'
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
Router::connect('/plain/sitemap',
	array(
		'controller' => 'sitemap',
		'action' => 'plain',
	)
);
/*
Router::connect('/zapchasti/sitemap_:page.xml.gz',
	array(
		'controller' => 'sitemap',
		'action' => 'subdomain_products',
	),
	array('pass' => array('page'))
);
*/
Router::connect('/zapchasti/sitemap',
	array(
		'controller' => 'sitemap',
		'action' => 'product_categories',
	)
);
Router::connect('/zapchasti/:slug/sitemap_:page.xml.gz',
	array(
		'controller' => 'sitemap',
		'action' => 'products',
	),
	array('pass' => array('slug', 'page'))
);

Router::connect('/robots.txt', array(
	'controller' => 'Robots',
	'action' => 'index',
));
