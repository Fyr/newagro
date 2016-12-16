<?
App::uses('AppModel', 'Model');
class Subdomain extends AppModel {

	public function getOptions() {
		$fields = array('id', 'name');
		$order = 'sorting';
		$aRowset = $this->find('list', compact('fields', 'order'));
		$aData = array('0' => __('- all sites -'));
		foreach($aRowset as $id => $name) {
			$aData[$id] = $name.'.'.Configure::read('domain.url');
		}
		return $aData;
	}

}
