<?php
App::uses('AppController', 'Controller');
App::uses('AuthComponent', 'Controller/Component');
App::uses('Sections', 'Model');
class AdminController extends AppController {
	public $name = 'Admin';
	public $layout = 'admin';
	// public $components = array();
	public $uses = array();

	public function _beforeInit() {
	    // auto-add included modules - did not included if child controller extends AdminController
	    $this->components = array_merge(array('Auth', 'Core.PCAuth', 'Table.PCTableGrid'), $this->components);
	    $this->helpers = array_merge(array('Html', 'Table.PHTableGrid', 'Form.PHForm'), $this->helpers);

		$this->aNavBar = array(
			'Content' => array('label' => __('Content'), 'href' => '', 'submenu' => array(
				array('label' => __('Static pages'), 'href' => array('controller' => 'AdminContent', 'action' => 'index', 'Page')),
				array('label' => __('News'), 'href' => array('controller' => 'AdminContent', 'action' => 'index', 'News')),
				array('label' => __('Offers'), 'href' => array('controller' => 'AdminContent', 'action' => 'index', 'Offer')),
				array('label' => __('Motors'), 'href' => array('controller' => 'AdminContent', 'action' => 'index', 'Motor')),
				array('label' => __('Articles'), 'href' => array('controller' => 'AdminContent', 'action' => 'index', 'SiteArticle')),
				array('label' => __('Machine tools'), 'href' => array('controller' => 'AdminContent', 'action' => 'index', 'MachineTool')),
			)),
			'SectionArticle' => array('label' => __('Sectionizer'), 'href' => '', 'submenu' => array()),
			'RepairArticle' => array('label' => __('Repair'), 'href' => array('controller' => 'AdminRepair', 'action' => 'index')),
			'Dealers' => array('label' => __('Dealers'), 'href' => array('controller' => 'AdminDealers', 'action' => 'index')),
			'Banners' => array('label' => __('Banners'), 'href' => array('controller' => 'AdminBanners', 'action' => 'index')),
			'Catalogs' => array('label' => __('Catalogs'), 'href' => array('controller' => 'AdminCatalogs', 'action' => 'index')),
			'Settings' => array('label' => __('Settings'), 'href' => '', 'submenu' => array(
				'SystemSettings' => array('label' => __('System'), 'href' => array('controller' => 'AdminSettings', 'action' => 'index')),
				'PriceSettings' => array('label' => __('Prices'), 'href' => array('controller' => 'AdminSettings', 'action' => 'prices')),
				'SectionSettings' => array('label' => __('Sectionizer'), 'href' => array('controller' => 'AdminSections', 'action' => 'index')),
				'Regions' => array('label' => __('Regions'), 'href' => array('controller' => 'AdminRegions', 'action' => 'index')),
				'Subdomains' => array('label' => __('Subdomains (Contacts)'), 'href' => array('controller' => 'AdminSubdomains', 'action' => 'index')),
				'Markers' => array('label' => __('Markers'), 'href' => array('controller' => 'AdminMarkers', 'action' => 'index')),
				'Messengers' => array('label' => __('Messengers'), 'href' => array('controller' => 'AdminMessengers', 'action' => 'index'))
			))
		);
		if (Configure::read('domain.zone') != 'ru') {
			unset($this->aNavBar['Settings']['submenu']['Regions']);
			unset($this->aNavBar['Settings']['submenu']['Subdomains']);
			unset($this->aNavBar['Settings']['submenu']['Markers']);
		}
		$this->aBottomLinks = $this->aNavBar;
	}

	public function beforeFilter() {
	    if (AuthComponent::user('id') && !$this->isAdmin()) {
	        return $this->redirect('/');
	    }
		$this->loadModel('Section');
		foreach($this->Section->getOptions() as $id => $title) {
			$this->aNavBar['SectionArticle']['submenu'][] = array(
				'label' => $title, 'href' => array('controller' => 'AdminSectionizer', 'action' => 'index', $id)
			);
		}
	    $this->currMenu = $this->_getCurrMenu();
	    $this->currLink = $this->currMenu;

	    $this->loadModel('User');
        $userID = AuthComponent::user('id');
        $this->set('currUser', $this->User->findById($userID));
	}

	public function beforeRenderLayout() {
		$this->set('isAdmin', $this->isAdmin());
	}

	public function isAdmin() {
		$user_id = AuthComponent::user('id');
		return $user_id == 1 || $user_id == 2;
	}

	public function index() {
		//$this->redirect(array('controller' => 'AdminProducts'));
	}

	protected function _getCurrMenu() {
		$curr_menu = str_ireplace('Admin', '', $this->request->controller); // By default curr.menu is the same as controller name
		return $curr_menu;
	}

	public function delete($id) {
		$this->autoRender = false;

		$model = $this->request->query('model');
		if ($model) {
			$this->loadModel($model);
			if (strpos($model, '.') !== false) {
				list($plugin, $model) = explode('.',$model);
			}

			$this->{$model}->delete($id);
		}
		if ($backURL = $this->request->query('backURL')) {
			$this->redirect($backURL);
			return;
		}
		$this->redirect(array('controller' => 'Admin', 'action' => 'index'));
	}

}
