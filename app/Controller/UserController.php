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

	public function changePassword() {
        if ($this->request->is(array('post', 'put'))) {
            $id = Hash::get($this->currUser, 'User.id');
            $password = $this->request->data('User.password');
            $password_confirm = $this->request->data('User.password_confirm');
            if ($this->User->save(compact('id', 'password', 'password_confirm'))) {
                $this->setAlert(__('New password has been successfully saved'));
                return $this->redirect(array('controller' => 'User', 'action' => 'changePassword'));
            }
        }
    }

    public function cart() {
        if ($this->request->is(array('post', 'put'))) {
            $user_id = Hash::get($this->currUser, 'User.id');
            $this->request->data('SiteOrder.user_id', $user_id);

            $zone = Configure::read('domain.zone');
            $this->request->data('SiteOrder.zone', Configure::read('domain.zone'));

            $email = Hash::get($this->currUser, 'User.email');

            if (Hash::get($this->currUser, 'User.group_id') == User::GROUP_COMPANY) {
                $username = Hash::get($this->currUser, 'UserCompany.contact_person');
                $phone = Hash::get($this->currUser, 'UserCompany.contact_phone');
            } else {
                $username = Hash::get($this->currUser, 'User.fio');
                $phone = Hash::get($this->currUser, 'User.phone');
            }

            $address = $this->request->data('SiteOrder.address');
            $comment = $this->request->data('SiteOrder.comment');

            $data = compact('user_id', 'zone', 'email', 'username', 'phone', 'address', 'comment');
            $site_order_id = $this->saveSiteOrder(array('SiteOrder' => $data));
            if ($site_order_id) {
                return $this->redirect(array('controller' => 'user', 'action' => 'success', $site_order_id));
            }
        } else {
            $this->request->data('SiteOrder.address', Hash::get($this->currUser, 'User.delivery_address'));
        }

        $this->set('aProducts', $this->getCartProducts());
    }

    public function success($id) {
        $this->loadModel('SiteOrder');
        $this->set('order', $this->SiteOrder->findById($id));
        $this->set('cartItems', array());
    }
}
