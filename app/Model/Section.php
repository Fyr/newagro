<?php
App::uses('AppModel', 'Model');
class Section extends AppModel {

	public function getOptions() {
		$order = 'sorting';
		return $this->find('list', compact('order'));
	}
}
