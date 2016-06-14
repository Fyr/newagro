<?
App::uses('AppModel', 'Model');
App::uses('Article', 'Article.Model');
App::uses('Media', 'Media.Model');
App::uses('MediaArticle', 'Model');
App::uses('SeoArticle', 'Model');
class Brand extends AppModel {
	public $useDbConfig = 'vitacars';
	public $useTable = 'articles';
	
	var $hasOne = array(
		'Seo' => array(
			'className' => 'SeoArticle',
			'foreignKey' => 'object_id',
			'conditions' => array('Seo.object_type' => 'Brand'),
			'dependent' => true
		),
		'MediaArticle' => array(
			'className' => 'MediaArticle',
			'foreignKey' => 'object_id',
			'conditions' => array('MediaArticle.media_type' => 'image', 'MediaArticle.object_type' => 'Brand', 'MediaArticle.main_by' => 1),
			'dependent' => true
		)
	);
	
	protected $objectType = 'Brand';
}
