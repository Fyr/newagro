<?php
App::uses('AdminController', 'Controller');
App::uses('AdminUser', 'Model');
App::uses('User', 'Model');
class AdminUsersController extends AdminController {
    public $name = 'AdminUsers';
    public $components = array('Auth', 'Table.PCTableGrid', 'Article.PCArticle');
    public $uses = array('AdminUser');

    public function beforeFilter() {
		if (!$this->isAdmin()) {
			$this->redirect(array('controller' => 'Admin', 'action' => 'index'));
			return;
		}
		parent::beforeFilter();
	}

    public function beforeRender() {
    	parent::beforeRender();
    	$this->set('objectType', 'AdminUser');
    }

    public function index() {
    	$this->paginate = array(
    		'fields' => array('id', 'created', 'username', 'active'),
    		'conditions' => array('zone' => Configure::read('domain.zone'), 'group_id' => User::GROUP_ADMIN)
    	);
    	$rows = $this->PCTableGrid->paginate('AdminUser');
    }

    public function edit($id = 0) {
    	if ($id) {
			if ($this->request->is(array('put', 'post')) && !$this->request->data('AdminUser.password')) {
				unset($this->request->data['AdminUser']['password']);
			}
		}
    	$this->PCArticle->setModel('AdminUser')->edit($id, $lSaved);
		if ($lSaved) {
			$id = $this->AdminUser->id;
			if ($id == AuthComponent::user('id')) {
				// перечитать данные для текущего юзера
				$user = $this->AdminUser->findById($id);
				$this->Auth->login($user['AdminUser']);
			}
			$baseRoute = array('action' => 'index');
			return $this->redirect(($this->request->data('apply')) ? $baseRoute : array($id));
		}
		if ($id) {
			$this->request->data('AdminUser.password', '');
		}
    }
}
