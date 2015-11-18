<?
App::uses('AppModel', 'Model');
App::uses('Article', 'Article.Model');
App::uses('Media', 'Media.Model');
App::uses('Seo', 'Seo.Model');
App::uses('Category', 'Model');

class Subcategory extends AppModel {
	public $useDbConfig = 'vitacars';
	public $useTable = 'articles';
	
	public $belongsTo = array(
		'Category' => array(
			'className' => 'Category',
			'foreignKey' => 'object_id',
			'dependent' => true
		)
	);
	
	var $hasOne = array(
		'Seo' => array(
			'className' => 'Seo.Seo',
			'foreignKey' => 'object_id',
			'conditions' => array('Seo.object_type' => 'Subcategory'),
			'dependent' => true
		)
	);
	
	protected $objectType = 'Subcategory';

}
