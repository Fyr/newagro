<?php
App::uses('AdminController', 'Controller');
class AdminSectionsController extends AdminController {
    public $name = 'AdminSections';
    public $uses = array('Section');
    
    public function index() {
    	$this->paginate['order'] = 'Section.sorting';
    	$this->PCTableGrid->paginate('Section');
    	$this->currMenu = 'Settings';
    }
    
    public function edit($id = 0) {
    	if ($this->request->is(array('put', 'post'))) {
    		$this->Section->save($this->request->data);
    		$id = $this->Section->id;
			$baseRoute = array('action' => 'index');
			return $this->redirect(($this->request->data('apply')) ? $baseRoute : array($id));
    	} else {
    		$this->request->data = $this->Section->findById($id);
    	}
    	
    	$this->currMenu = 'Settings';
    	if (!$this->request->data('Section.sorting')) {
			$this->request->data('Section.sorting', '0');
		}
    }
}
