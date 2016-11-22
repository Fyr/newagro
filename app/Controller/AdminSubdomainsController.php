<?php
App::uses('AdminController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('Subdomain', 'Model');
App::uses('Region', 'Model');
class AdminSubdomainsController extends AdminController {
    public $name = 'AdminSubdomains';
    public $components = array('Auth', 'Table.PCTableGrid', 'Article.PCArticle');
    public $uses = array('Subdomain', 'Region');

	public function beforeRender() {
		parent::beforeRender();
		$this->set('aRegions', $this->Region->getOptions());
	}

	public function index() {
		$this->paginate = array(
			'fields' => array('name', 'title', 'region_id', 'email', 'skype'),
			'order' => array('region_id' => 'ASC')
		);
		$this->PCTableGrid->paginate('Subdomain');
		$this->currMenu = 'Settings';
	}

	public function edit($id = 0) {
		if ($this->request->is(array('put', 'post'))) {
			$this->Subdomain->save($this->request->data);
			$id = $this->Subdomain->id;
			$baseRoute = array('action' => 'index');
			return $this->redirect(($this->request->data('apply')) ? $baseRoute : array($id));
		} else {
			$this->request->data = $this->Subdomain->findById($id);
		}

		$this->currMenu = 'Settings';
		if (!$this->request->data('Subdomain.sorting')) {
			$this->request->data('Subdomain.sorting', '0');
		}
	}
}

