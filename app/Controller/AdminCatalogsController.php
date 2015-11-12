<?php
App::uses('AdminController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('Catalog', 'Model');
App::uses('PHMedia', 'Media.View/Helper');
class AdminCatalogsController extends AdminController {
    public $name = 'AdminCatalogs';
    public $components = array('Auth', 'Table.PCTableGrid', 'Article.PCArticle');
    public $uses = array('Catalog');
    public $helpers = array('Media.PHMedia');
    
    public function beforeRender() {
    	parent::beforeRender();
    	$this->set('objectType', 'Catalog');
    }
    
    public function index() {
    	$this->paginate = array(
    		'fields' => array('title', 'url', 'published', 'sorting')
    	);
    	$aRowset = $this->PCTableGrid->paginate('Catalog');
    	$this->set('aRowset', $aRowset);
    }
    
    public function edit($id = 0) {
    	$this->PCArticle->setModel('Catalog')->edit(&$id, &$lSaved);
		if ($lSaved) {
			$baseRoute = array('action' => 'index');
			return $this->redirect(($this->request->data('apply')) ? $baseRoute : array($id));
		}
		if (!$id) {
			$this->request->data('Catalog.sorting', 1);
		}
    }
}

