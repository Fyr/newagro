<?php
App::uses('AppController', 'Controller');
App::uses('UserAuthComponent', 'Controller/Component');
App::uses('User', 'Model');
class UserController extends AppController {
	public $name = 'User';
	public $components = array('UserAuth');
	public $uses = array('User');
	public $layout = 'login_user';

	protected function beforeFilterLayout() {
		$this->initNavBar();

		$this->currMenu = $this->_getCurrMenu();
	    $this->currLink = $this->currMenu;

		$this->set('isHomePage', false);
		$this->set('cartItems', $this->getCartItems());
		$this->set('userGroups', $this->User->getAccountTypeOptions());
	}

	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				return $this->redirect($this->Auth->loginRedirect);
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
