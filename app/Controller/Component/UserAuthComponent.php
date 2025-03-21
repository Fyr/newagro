<?php
App::uses('Component', 'Controller');
class UserAuthComponent extends Component {
    private $_;

    public function initialize(Controller $controller)  {
        $this->_ = $controller;
        $this->_->Auth = $this->_->Components->load('Auth');
        $this->_->Auth->initialize($this->_);

        $this->_->Auth->authorize = array('Controller');
		$this->_->Auth->loginAction = array('plugin' => '', 'controller' => 'user', 'action' => 'login');
		$this->_->Auth->loginRedirect = array('plugin' => '', 'controller' => 'user', 'action' => 'index');
		$this->_->Auth->logoutRedirect = '/';
		$this->_->Auth->authError = __('You must log in to access this page');
		// $this->_->Auth->ajaxLogin = 'Core.ajax_auth_failed';
    }
}
