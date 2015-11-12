<?php
App::uses('AdminController', 'Controller');
App::uses('Article', 'Article.Model');
App::uses('AppModel', 'Model');
App::uses('Dealer', 'Model');
class AdminDealersController extends AdminController {
    public $name = 'AdminDealers';
    public $components = array('Article.PCArticle');
    public $uses = array('Article.Article', 'Dealer');
    public $helpers = array('ObjectType');
    
    public function beforeFilter() {
    	$this->set('objectType', 'Dealer');
    	return parent::beforeFilter();
    }
    
    public function index() {
        $this->paginate = array(
        	'Dealer' => array(
        		'fields' => array('created', 'title', 'slug', 'DealerTable.address', 'DealerTable.phones', 'DealerTable.site_url', 'DealerTable.email', 'published')
        	)
        );
        $aRowset = $this->PCArticle->setModel('Dealer')->index();
        $this->set('aRowset', $aRowset);
    }
    
	public function edit($id = 0, $objectType = '', $objectID = '') {
		$objectType = 'Dealer';
		$this->loadModel('Media.Media');
		
		if (!$id) {
			// если не задан ID, то objectType+ObjectID должны передаваться
			$this->request->data('Article.object_type', $objectType);
			$this->request->data('Seo.object_type', 'Page');
		}
		
		$this->PCArticle->setModel('Dealer')->edit(&$id, &$lSaved);
		
		if ($lSaved) {
			$indexRoute = array('action' => 'index');
			$editRoute = array('action' => 'edit', $id);
			return $this->redirect(($this->request->data('apply')) ? $indexRoute : $editRoute);
		}

	}
}
