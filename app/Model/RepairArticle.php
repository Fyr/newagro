<?php
App::uses('AppModel', 'Model');
App::uses('Article', 'Article.Model');
App::uses('Seo', 'Seo.Model');
App::uses('Section', 'Model');

class RepairArticle extends Article {
	protected $objectType = 'RepairArticle';

	var $hasOne = array(
		'Media' => array(
			'className' => 'Media.Media',
			'foreignKey' => 'object_id',
			'conditions' => array('Media.media_type' => 'image', 'Media.object_type' => 'RepairArticle', 'Media.main' => 1),
			'dependent' => true
		),
		'Seo' => array(
			'className' => 'Seo.Seo',
			'foreignKey' => 'object_id',
			'conditions' => array('Seo.object_type' => 'Page'),
			'dependent' => true
		)
	);

	public function getOptions() {
		$conditions = array('RepairArticle.cat_id' => 0);
		$order = 'RepairArticle.sorting';
		return $this->find('list', compact('conditions', 'order'));
	}
}
