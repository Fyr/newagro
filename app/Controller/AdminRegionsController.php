<?php
App::uses('AdminController', 'Controller');
App::uses('Region', 'Model');
class AdminRegionsController extends AdminController {
    public $name = 'AdminRegions';
    public $components = array('Auth', 'Table.PCTableGrid', 'Article.PCArticle');
    public $uses = array('Region');

    public function beforeFilter() {
		if (!$this->isAdmin()) {
			$this->redirect(array('controller' => 'Admin', 'action' => 'index'));
			return;
		}
		parent::beforeFilter();
	}

    public function beforeRender() {
    	parent::beforeRender();
    	$this->set('objectType', 'Region');
    }

    public function index() {
    	$this->paginate = array(
    		'fields' => array('id', 'title')
    	);
    	$this->PCTableGrid->paginate('Region');
    }

    public function edit($id = 0) {
    	$this->PCArticle->setModel('Region')->edit($id, $lSaved);
		if ($lSaved) {
			$baseRoute = array('action' => 'index');
			return $this->redirect(($this->request->data('apply')) ? $baseRoute : array($id));
		}
    }
}
