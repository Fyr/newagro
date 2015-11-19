<?
App::uses('AppModel', 'Model');
App::uses('Article', 'Article.Model');
App::uses('Media', 'Media.Model');
App::uses('SeoArticle', 'Model');
class Category extends AppModel {
	public $useDbConfig = 'vitacars';
	public $useTable = 'articles';
	
	var $hasOne = array(
		'Seo' => array(
			'className' => 'SeoArticle',
			'foreignKey' => 'object_id',
			'conditions' => array('Seo.object_type' => 'Category'),
			'dependent' => true
		)
	);
	
	protected $objectType = 'Category';

}
