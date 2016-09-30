<?
App::uses('AppModel', 'Model');
class Subdomain extends AppModel {

	public function getOptions() {
		$order = 'sorting';
		return $this->find('list', compact('order'));
	}

}
