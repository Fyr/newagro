<?php
App::uses('Model', 'Model');
class AppModel extends Model {
	
	protected $objectType = '', $altDbConfig = false;
	
	public function __construct($id = false, $table = null, $ds = null) {
		$this->_beforeInit();
	    parent::__construct($id, $table, $ds);
	    $this->_afterInit();
	}
	
	protected function _beforeInit() {
	    // Add here behaviours, models etc that will be also loaded while extending child class
		if ($this->altDbConfig) {
			if ($this->getDomain() !== $this->altDbConfig) {
				$this->useDbConfig = $this->altDbConfig;
			}
		}
		if (isset($this->hasOne['MediaArticle']) && isset($this->hasOne['MediaArticle']['conditions'])
				&& isset($this->hasOne['MediaArticle']['conditions']['MediaArticle.main_$zone'])) {
			$this->hasOne['MediaArticle']['conditions']['MediaArticle.main_'.Configure::read('domain.zone')] = 1;
			unset($this->hasOne['MediaArticle']['conditions']['MediaArticle.main_$zone']);
		}
	}

	protected function _afterInit() {
	    // after construct actions here
	}
	
	/**
	 * Auto-add object type in find conditions
	 *
	 * @param array $query
	 * @return array
	 */
	public function beforeFind($query) {
		if ($this->objectType) {
			$query['conditions'][$this->objectType.'.object_type'] = $this->objectType;
		}
		return $query;
	}
	
	public function loadModel($modelClass = null, $id = null) {
		list($plugin, $modelClass) = pluginSplit($modelClass, true);
		
		$this->{$modelClass} = ClassRegistry::init(array(
			'class' => $plugin . $modelClass, 'alias' => $modelClass, 'id' => $id
		));
		if (!$this->{$modelClass}) {
			throw new MissingModelException($modelClass);
		}
		
		return $this->{$modelClass};
	}
	
	private function _getObjectConditions($objectType = '', $objectID = '') {
		$conditions = array();
		if ($objectType) {
			$conditions[$this->alias.'.object_type'] = $objectType;
		}
		if ($objectID) {
			$conditions[$this->alias.'.object_id'] = $objectID;
		}
		return compact('conditions');
	}
	
	public function getObjectOptions($objectType = '', $objectID = '', $order = array()) {
		$conditions = array_values($this->_getObjectConditions($objectType, $objectID));
		return $this->find('list', compact('conditions', 'order'));
	}
	
	public function getObject($objectType = '', $objectID = '') {
		return $this->find('first', $this->_getObjectConditions($objectType, $objectID));
	}
	
	public function getObjectList($objectType = '', $objectID = '', $order = array()) {
		$conditions = array_values($this->_getObjectConditions($objectType, $objectID));
		return $this->find('all', compact('conditions', 'order'));
	}
	
	public function dateRange($field, $date1, $date2 = '') {
		// TODO: implement for free date2
		$date1 = date('Y-m-d 00:00:00', strtotime($date1));
		$date2 = date('Y-m-d 23:59:59', strtotime($date2));
		return array($field.' >= ' => $date1, $field.' <= ' => $date2);
	}
	
	public function dateTimeRange($field, $date1, $date2 = '') {
		// TODO: implement for free date2
		$date1 = date('Y-m-d H:i:s', strtotime($date1));
		$date2 = date('Y-m-d H:i:s', strtotime($date2));
		return array($field.' >= ' => $date1, $field.' <= ' => $date2);
	}
	
	public function getRandomRows($count = 1, $aOptions = array()) {
		if (!isset($aOptions['conditions'])) {
			$aListOptions['conditions'] = $aOptions;
		} else {
			$aListOptions = $aOptions;
		}
		$list = $this->find('list', $aListOptions); // TODO: if number of recs > 10000, divide all row set into pages and limit row set due to page
		$aID = array_keys($list); // $this->getIDList($list, $this->primaryKey, $this->name)
		shuffle($aID);
		$aID = array_slice($aID, 0, $count);
		return $this->find('all', array('conditions' => array($this->alias.'.'.$this->primaryKey => $aID), 'order' => 'rand()'));
	}

	public function getTableName() {
		return $this->getDataSource()->fullTableName($this);
	}

	public function setTableName($table) {
		$this->setSource($table);
	}

	public function trxBegin() {
		$this->getDataSource()->begin();
	}
	
	public function trxCommit() {
		$this->getDataSource()->commit();
	}
	
	public function trxRollback() {
		$this->getDataSource()->rollback();
	}
	
	public function isBot($ip = '') {
		if (!$ip) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		$hostname = gethostbyaddr($ip);
		return ($hostname === 'spider-'.str_replace('.', '-', $ip).'.yandex.com') 
			|| ($hostname === 'crawl-'.str_replace('.', '-', $ip).'.googlebot.com');
	}

	public function getDomain() {
		list($domain) = explode('.', Configure::read('domain.url'));
		return $domain;
	}
}
