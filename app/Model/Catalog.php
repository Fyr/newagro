<?
App::uses('AppModel', 'Model');
class Catalog extends AppModel {
	var $name = 'Catalog';
	
	var $hasMany = array(
		'Media' => array(
			'className' => 'Media',
			'foreignKey' => 'object_id',
			'conditions' => array('Media.object_type' => 'Catalog', 'Media.media_type IN ("image", "raw_file")'),
			'dependent' => true,
			'order' => array('Media.main DESC', 'media_type', 'id')
		)
	);
	
}
