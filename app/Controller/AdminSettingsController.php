<?php
App::uses('AdminController', 'Controller');
App::uses('Settings', 'Model');
class AdminSettingsController extends AdminController {
    public $name = 'AdminSettings';
    public $uses = array('Settings');

    /*
    public function beforeFilter() {

		parent::beforeFilter();
	}
    */

    public function index() {
        if ($this->request->is('post') || $this->request->is('put')) {
        	$this->request->data('Settings.id', 1);
        	$this->Settings->save($this->request->data);
            $this->setFlash('Settings are saved', 'success');
        	return $this->redirect(array('action' => 'index'));
        }
        $this->request->data = $this->Settings->getData();
    }
    
    public function prices() {
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data('Settings.id', 1);
            $this->Settings->save($this->request->data);
            $this->setFlash('Settings are saved', 'success');
            return $this->redirect(array('action' => 'prices'));
        }
        $this->request->data = $this->Settings->getData();
    }
}
