<?php
Router::parseExtensions('json');
Router::connect('/', array('controller' => 'Pages', 'action' => 'home'));

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


CakePlugin::routes();

require CAKE.'Config'.DS.'routes.php';
