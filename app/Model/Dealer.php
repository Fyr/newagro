<?php
App::uses('AppModel', 'Model');
App::uses('Article', 'Article.Model');
App::uses('Seo', 'Seo.Model');
class Dealer extends Article {
	protected $objectType = 'Dealer';
	
	public $belongsTo = array(
		'DealerTable' => array(
			'className' => 'DealerTable',
			'foreignKey' => 'object_id',
			'dependent' => true
		)
	);
	
	public $hasOne = array(
		'Seo' => array(
			'className' => 'Seo.Seo',
			'foreignKey' => 'object_id',
			'conditions' => array('Seo.object_type' => 'Page'),
			'dependent' => true
		)
	);
}
