<?php
App::uses('AdminController', 'Controller');
App::uses('Settings', 'Model');
class AdminSettingsController extends AdminController {
    public $name = 'AdminSettings';
    public $uses = array('Settings');

    public function beforeFilter() {
		parent::beforeFilter();

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data('Settings.id', 1);
            $this->Settings->save($this->request->data);
            $this->setFlash(__('Settings are saved'), 'success');
            return $this->redirect(array('action' => 'index'));
        }
        $this->request->data = $this->Settings->getData();
	}

    public function index() {
    }

    public function contacts() {
    }
    
    public function prices() {
    }
}
