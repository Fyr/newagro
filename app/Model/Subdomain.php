<?
App::uses('AppModel', 'Model');
class Subdomain extends AppModel {

	public function getOptions() {
		$fields = array('id', 'name');
		$order = 'sorting';
		$aRowset = $this->find('list', compact('fields', 'order'));
		$aData = array('0' => __('- www -'));
		foreach($aRowset as $id => $name) {
			$aData[$id] = Configure::read('domain.url').'/filial/'.$name;
		}
		return $aData;
	}

}
