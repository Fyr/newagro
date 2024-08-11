<?php
App::uses('AppModel', 'Model');
class Article extends AppModel {
	public $useTable = 'pages';

	public $validate = array(
		'title' => 'notempty'
	);

	public function beforeDelete($cascade = true) {
		if ($cascade && isset($this->hasOne) && isset($this->hasOne['Media'])) {
			$hasMedia = $this->hasOne['Media'];
			if (isset($hasMedia['conditions']) && isset($hasMedia['conditions']['Media.object_type'])) {
				// remove all media files instead of one no matter if "dependent" flag is set or not
				$conditions = array(
					'object_type' => $hasMedia['conditions']['Media.object_type'],
					'object_id' => $this->id
				);
				$aMedia = $this->Media->getList($conditions);
				foreach($aMedia as $media) {
					$this->Media->delete($media['Media']['id']);
				}
			}
		}
	}
}
