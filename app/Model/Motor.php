<?php
App::uses('AppModel', 'Model');
App::uses('Article', 'Article.Model');
App::uses('Seo', 'Seo.Model');
class Motor extends Article {
	protected $objectType = 'Motor';
	
	public $hasOne = array(
		'Seo' => array(
			'className' => 'Seo.Seo',
			'foreignKey' => 'object_id',
			'conditions' => array('Seo.object_type' => 'Page'),
			'dependent' => true
		)
	);
}
