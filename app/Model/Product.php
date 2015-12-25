<?
App::uses('AppModel', 'Model');
App::uses('Article', 'Article.Model');
App::uses('Media', 'Media.Model');
App::uses('PMFormData', 'Form.Model');
App::uses('SeoArticle', 'Model');
App::uses('Category', 'Model');
App::uses('Subcategory', 'Model');
class Product extends Article {
	public $useDbConfig = 'vitacars';
	public $useTable = 'articles';
	
	const NUM_DETAIL = 5;
	const MOTOR = 6;
	
	public $belongsTo = array(
		'Category' => array(
			'foreignKey' => 'cat_id'
		),
		'Subcategory' => array(
			'foreignKey' => 'subcat_id'
		)
	);
	
	public $hasOne = array(
		'MediaArticle' => array(
			'className' => 'MediaArticle',
			'foreignKey' => 'object_id',
			'conditions' => array('MediaArticle.media_type' => 'image', 'MediaArticle.object_type' => 'Product', 'MediaArticle.main' => 1),
			'dependent' => true
		),
		'Seo' => array(
			'className' => 'SeoArticle',
			'foreignKey' => 'object_id',
			'conditions' => array('Seo.object_type' => 'Product'),
			'dependent' => true
		),
		'Search' => array(
			'foreignKey' => 'id',
			'dependent' => true
		)
	);
	
	public $objectType = 'Product';
	
}
