<?php
App::uses('AppController', 'Controller');
App::uses('UserAuthComponent', 'Controller/Component');
App::uses('User', 'Model');
class UserController extends AppController {
	public $name = 'User';
	public $components = array('UserAuth');
	public $uses = array('User');
	public $layout = 'login_user';

	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				return $this->redirect($this->Auth->redirect());
			} else {
				$this->Session->setFlash(AUTH_ERROR, null, null, 'auth');
			}
		}
		$this->leftSidebar = false;
	}

	public function logout() {
		$this->redirect($this->Auth->logout());
	}

	public function index() {
	}
}
