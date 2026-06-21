<?php
App::uses('AppModel', 'Model');
App::uses('Article', 'Article.Model');
App::uses('Seo', 'Seo.Model');
App::uses('Media', 'Media.Model');
class Page extends Article {
	protected $objectType = 'Page';

	public $hasOne = array(
		'Seo' => array(
			'className' => 'Seo.Seo',
			'foreignKey' => 'object_id',
			'conditions' => array('Seo.object_type' => 'Page'),
			'dependent' => true
		),
		'Media' => array(
            'className' => 'Media.Media',
            'foreignKey' => 'object_id',
            'conditions' => array('Media.media_type' => 'image', 'Media.object_type' => 'Page', 'Media.main' => 1),
            'dependent' => true
        ),
	);
}
