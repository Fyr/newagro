<?
App::uses('AppModel', 'Model');
class Subdomain extends AppModel {

	public function getOptions() {
		$order = 'sorting';
		$aRowset = $this->find('list', compact('order'));
		$aData = array('0' => __('- all sites -'));
		foreach($aRowset as $id => $name) {
			$aData[$id] = $name.'.'.Configure::read('domain.url');
		}
		return $aData;
	}

}
