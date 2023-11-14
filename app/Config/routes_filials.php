<?php
// Home page for filшals
Router::connect('/filial/:filial', 
    array('controller' => 'Pages', 'action' => 'home'),
    array('named' => array('filial'))
);
// About us page for filials
Router::connect('/filial/:filial/pages/show/about-us', 
    array('controller' => 'Pages', 'action' => 'show', 'about-us'),
    array('named' => array('filial'))
);
// Contacts page for filials
Router::connect('/filial/:filial/contacts', 
    array('controller' => 'Contacts', 'action' => 'index'),
    array('named' => array('filial'))
);

/* -= News =- */
Router::connect('/filial/:filial/news', array(
		'controller' => 'Articles', 
		'action' => 'index',
		'objectType' => 'News',
	),
	array('named' => array('filial', 'page' => 1))
);
Router::connect('/filial/:filial/news/:slug', 
	array(
		'controller' => 'Articles', 
		'action' => 'view',
		'objectType' => 'News'
	),
	array(
        'named' => array('filial'),
        'pass' => array('slug')
    )
);
Router::connect('/filial/:filial/news/page/:page', array(
        'controller' => 'Articles', 
        'action' => 'index',
        'objectType' => 'News'
    ),
	array('named' => array('filial', 'page' => '[\d]*'))
);

/* -= Offers =- */
Router::connect('/filial/:filial/offers', array(
		'controller' => 'Articles', 
		'action' => 'index',
		'objectType' => 'Offer',
	),
	array('named' => array('filial', 'page' => 1))
);
Router::connect('/filial/:filial/offers/:slug', 
	array(
		'controller' => 'Articles', 
		'action' => 'view',
		'objectType' => 'Offer'
	),
	array(
        'named' => array('filial'),
        'pass' => array('slug')
    )
);
Router::connect('/filial/:filial/offers/page/:page', array(
	'controller' => 'Articles', 
	'action' => 'index',
	'objectType' => 'Offer'
),
	array('named' => array('filial', 'page' => '[\d]*'))
);