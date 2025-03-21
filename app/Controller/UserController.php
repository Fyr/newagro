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
		$this->initNavBarView();

		$this->currMenu = $this->_getCurrMenu();
	    $this->currLink = $this->currMenu;

		$this->set('isHomePage', false);
		$this->set('cartItems', $this->getCartItems());
		$this->set('userGroups', $this->User->getAccountTypeOptions());
	}

	protected function setAlert($message, $type = 'info') {
	    $this->Session->setFlash($message, null, null, 'info');
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

	public function profile() {
	    if ($this->request->is(array('post', 'put'))) {
	        // to correct save and prevent client's POST data corrupting
	        $this->request->data('User.id', Hash::get($this->currUser, 'User.id'));
            $userGroup = Hash::get($this->currUser, 'User.group_id');
            $isValid = false;
            if ($userGroup == User::GROUP_COMPANY) {
                $this->request->data('UserCompany.id', Hash::get($this->currUser, 'UserCompany.id'));
                $isValid = $this->User->saveAll($this->request->data);
            } else {
                $isValid = $this->User->save($this->request->data);
            }
            if ($isValid) {
                $this->setAlert(__('Profile has been successfully saved'));
	            return $this->redirect(array('controller' => 'User', 'action' => 'profile'));
	        }
	    }
	    $this->request->data = $this->currUser;
	}

	public function delivery() {
	    if ($this->request->is(array('post', 'put'))) {
	        $id = Hash::get($this->currUser, 'User.id');
	        $delivery_address = $this->request->data('User.delivery_address');
	        $this->User->save(compact('id', 'delivery_address'));
	        $this->setAlert(__('Delivery address has been successfully saved'));
	        return $this->redirect(array('controller' => 'User', 'action' => 'delivery'));
	    }
	    $this->request->data = $this->currUser;
	}
}
