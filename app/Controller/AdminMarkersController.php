<?php
App::uses('AdminController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('Subdomain', 'Model');
App::uses('Region', 'Model');
class AdminMarkersController extends AdminController {
    public $name = 'AdminMarkers';
    public $components = array('Auth', 'Table.PCTableGrid', 'Article.PCArticle');
    public $uses = array('Marker', 'Subdomain', 'Region');

	public function beforeRender() {
		parent::beforeRender();
		$this->set('objectType', 'Marker');
		$this->set('aRegions', $this->Region->getOptions());
	}

	public function index() {
		$this->paginate = array(
			'fields' => array('title', 'region_id', 'url'),
			'order' => array('region_id' => 'ASC')
		);
		$this->PCTableGrid->paginate('Marker');
		$this->currMenu = 'Settings';
	}

	public function edit($id = 0) {
		if ($this->request->is(array('put', 'post'))) {
			if ($this->Marker->save($this->request->data)) {
				// clean plain pages cache
				$this->_cleanCache('plain.xml');

				$id = $this->Marker->id;
				$baseRoute = array('action' => 'index');
				return $this->redirect(($this->request->data('apply')) ? $baseRoute : array($id));
			}
		} else {
			$this->request->data = $this->Marker->findById($id);
		}

		$this->currMenu = 'Settings';
	}
}

