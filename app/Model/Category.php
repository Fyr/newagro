<?
App::uses('AppModel', 'Model');
App::uses('Article', 'Article.Model');
App::uses('Media', 'Media.Model');
App::uses('Seo', 'Seo.Model');
class Category extends AppModel {
	public $useDbConfig = 'vitacars';
	public $useTable = 'articles';
	
	var $hasOne = array(
		'Seo' => array(
			'className' => 'Seo.Seo',
			'foreignKey' => 'object_id',
			'conditions' => array('Seo.object_type' => 'Category'),
			'dependent' => true
		)
	);
	
	protected $objectType = 'Category';

}
