<?php
App::uses('AdminController', 'Controller');
class AdminMessengersController extends AdminController {
    public $name = 'AdminMessengers';
    public $uses = array('Messenger');

    public function beforeFilter() {
        parent::beforeFilter();
        $aTypeOptions = $this->Messenger->getTypeOptions();
        $this->set(compact('aTypeOptions'));
    }
    
    public function index() {
    	$this->paginate['order'] = 'Messenger.sorting';
    	$this->PCTableGrid->paginate('Messenger');
    	$this->currMenu = 'Settings';
    }
    
    public function edit($id = 0) {
    	if ($this->request->is(array('put', 'post'))) {
    		$this->Messenger->save($this->request->data);
    		$id = $this->Section->id;
			$baseRoute = array('action' => 'index');
			return $this->redirect(($this->request->data('apply')) ? $baseRoute : array($id));
    	} else {
    		$this->request->data = $this->Messenger->findById($id);
            if (!$id) {
                $this->request->data('Messenger.active', '1');
                $this->request->data('Messenger.sorting', '0');
                $this->request->data('Messenger.used', '0');
            }
    	}
    	
    	$this->currMenu = 'Settings';
    }
}
