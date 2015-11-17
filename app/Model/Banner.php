<?
App::uses('AppModel', 'Model');
App::uses('Media', 'Media.Model');
class Banner extends AppModel {
	var $name = 'Banner';
	
	var $hasMany = array(
		'Media' => array(
			'className' => 'Media.Media',
			'foreignKey' => 'object_id',
			'conditions' => array('Media.object_type' => 'Banner', 'Media.media_type' => 'image'),
			'dependent' => true,
			'order' => array('Media.main DESC', 'media_type', 'id')
		)
	);
	
	public function beforeSave($options = array()) {
		$this->data['Banner']['options'] = serialize($this->data['Banner']['options']);
		return true;
	}
	
	public function afterFind($results, $primary = false) {
		if ($primary) {
			foreach($results as &$res) {
				if (isset($res['Banner']) && isset($res['Banner']['options'])) {
					$res['Banner']['options'] = unserialize($res['Banner']['options']);
				}
			}
		}
		return $results;
	}
}
