<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');
class UserController extends AppController {
	public $name = 'User';
	public $components = array('Core.PCAuth');
	public $uses = array('User');
	public $layout = 'login_user';

	public $leftSidebar = false;

	public function login() {
		if ($this->request->is('post')) {
		    fdebug("->login()\r\n");
			if ($this->Auth->login()) {
				return $this->redirect(array('action' => 'register'));
			} else {
				$this->Session->setFlash(AUTH_ERROR, null, null, 'auth');
			}
		}
	}

	public function logout() {
		// $this->redirect($this->Auth->logout());
	}

	public function register() {
		if ($this->request->is(array('post', 'put'))) {
		    $this->request->data('User.username', $this->request->data('User.email'));
		    $isValid = false;
		    if ($this->request->data('User.group_id') == User::GROUP_COMPANY) {
		        $this->request->data('User.fio', '');
		        $this->request->data('User.phone', '');
		        $isValid = $this->User->saveAll($this->request->data);
            } else {
		        $isValid = $this->User->save($this->request->data('User'));
            }

			if ($isValid) {
				fdebug("FORM VALID\r\n");
			} else {
				fdebug("FORM INVALID\r\n");
				$errors = $this->User->validationErrors;
				fdebug($errors);
			}
		}

		$this->set('accountTypeOptions', $this->User->getAccountTypeOptions());
		// $this->redirect($this->Auth->logout());
	}
}
