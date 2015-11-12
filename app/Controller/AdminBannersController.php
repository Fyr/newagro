<?php
App::uses('AdminController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('Banner', 'Model');
App::uses('BannerType', 'Model');
App::uses('SlotPlace', 'Model');
class AdminBannersController extends AdminController {
    public $name = 'AdminBanners';
    public $components = array('Auth', 'Table.PCTableGrid', 'Article.PCArticle');
    public $uses = array('Banner', 'BannerType', 'SlotPlace');
    
    public function beforeRender() {
    	parent::beforeRender();
    	$this->set('objectType', 'Banner');
    }
    
    public function index() {
    	$this->paginate = array(
    		'fields' => array('id', 'title', 'type', 'slot', 'sorting', 'active')
    	);
    	$aRowset = $this->PCTableGrid->paginate('Banner');
    	$this->set('aRowset', $aRowset);
    	$this->set('bannerTypes', $this->BannerType->getOptions());
    }
    
    public function edit($id = 0) {
    	$this->PCArticle->setModel('Banner')->edit(&$id, &$lSaved);
		if ($lSaved) {
			$baseRoute = array('action' => 'index');
			return $this->redirect(($this->request->data('apply')) ? $baseRoute : array($id));
		}
		if (!$id) {
			$this->request->data('Banner.sorting', 1);
		}
		
		$this->set('bannerTypes', $this->BannerType->getOptions());
		$this->set('slotPlaces', $this->SlotPlace->getOptions());
    }
}

