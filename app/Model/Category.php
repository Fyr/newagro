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
		),
		'Media' => array(
			'className' => 'Media.Media',
			'foreignKey' => 'object_id',
			'conditions' => array('Media.media_type' => 'image', 'Media.object_type' => 'Category', 'Media.main' => 1),
			'dependent' => true
		)
	);
	
	protected $objectType = 'Category';

	public function getTypesList() {
		$types = $this->getObjectList(
			array('Category', 'Subcategory'),
			'',
			array('Category.object_id', 'Category.sorting')
		);
		$aTypes = array();
		foreach($types as $type) {
			$aTypes['type_'.$type['Category']['object_id']][] = $type['Category'];
		}
		return $aTypes;
	}

}
