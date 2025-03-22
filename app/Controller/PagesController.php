<?php
App::uses('AppController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('Page', 'Model');
App::uses('News', 'Model');
App::uses('SiteArticle', 'Model');
App::uses('Region', 'Model');
App::uses('Category', 'Model');
App::uses('Product', 'Model');
App::uses('User', 'Model');
class PagesController extends AppController {
	public $name = 'Pages';
	public $uses = array('Page', 'News', 'Region', 'Marker', 'Category', 'Product');
	public $helpers = array('ArticleVars', 'Media.PHMedia', 'Core.PHTime', 'Media');

	public function home() {
		$this->set('isHomePage', true);
		$subdomain = $this->getSubdomainId();
		$conditions = array('News.published' => 1, 'News.subdomain_id' => array(SUBDOMAIN_ALL, $subdomain));
		if ($this->aEvents) {
			$conditions['NOT'] = array('News.id' => Hash::extract($this->aEvents, '{n}.News.id'));
		}
		$aNews = $this->News->find('all', array(
			'conditions' => $conditions,
			'order' => array('News.subdomain_id DESC', 'News.featured DESC', 'News.sorting ASC', 'News.created DESC'),
			'limit' => 3
		));
		$this->set('aHomePageNews', $aNews);

		$this->Product->bindModel(array('hasOne' => array('PMFormData' => array(
			'className' => 'Form.PMFormData',
			'foreignKey' => 'object_id',
			'conditions' => array('PMFormData.object_type' => 'ProductParam'),
			'dependent' => true
		))), false);
		$conditions = array('Product.published' => 1, 'Product.featured_'.Configure::read('domain.zone') => 1);
		$order = array('Product.modified' => 'DESC');
		$aFeaturedProducts = $this->Product->find('all', compact('conditions', 'order'));

		$aArticle = $this->Page->getBySlug('home');
		$aArticle2 = $this->Page->getBySlug('home2');
		$this->set('contentArticle', $aArticle);
		$this->set('contentArticle2', $aArticle2);

		if (!(isset($aArticle['Seo']) & isset($aArticle['Seo']['title']) && $aArticle['Seo']['title'])) {
			$aArticle['Seo']['title'] = $aArticle['Page']['title'];
		}
		$this->seo = $aArticle['Seo'];

		$aRegions = Hash::combine($this->Region->find('all'), '{n}.Region.id', '{n}.Region');
		$aMarkers = $this->Marker->find('all');
		$aMarkers = Hash::combine($aMarkers, '{n}.Marker.id', '{n}.Marker', '{n}.Marker.region_id'); // group by region
		$this->set(compact('aRegions', 'aMarkers', 'aFeaturedProducts'));
	}

	public function show($slug) {
		$aArticle = $this->Page->getBySlug($slug);
		if (!$aArticle) {
			$this->redirect404();
			return;
		}
		$this->set('aArticle', $aArticle);

		if (!(isset($aArticle['Seo']) & isset($aArticle['Seo']['title']) && $aArticle['Seo']['title'])) {
			$aArticle['Seo']['title'] = $aArticle['Page']['title'];
		}
		$this->seo = $aArticle['Seo'];
		$this->currMenu = $slug;
	}

	public function nonExist() {
		$this->render('/Errors/error400');
	}

	public function region($id) {
		$region = $this->Region->findById($id);
		if (!$region) {
			$this->redirect404();
			return;
		}

		$conditions = array('region_id' => $id);
		$order = 'sorting';
		$aSubdomains = $this->Subdomain->find('all', compact('conditions', 'order'));
		$this->set(compact('region', 'aSubdomains'));
	}

    public function register() {
        $this->loadModel('User');
		if ($this->request->is('post')) {
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
			    return $this->redirect(array('controller' => 'user', 'action' => 'login'));
			}
		}

		$this->set('accountTypeOptions', $this->User->getAccountTypeOptions());
		$this->layout = 'login_user';
		$this->leftSidebar = false;
	}

	public function forgotPassword() {
	    $this->loadModel('PasswordRecover');
	    $this->loadModel('User');
        if ($this->request->is('post')) {
            $this->PasswordRecover->set($this->request->data);
            if ($this->PasswordRecover->validates()) {
                $password = str_shuffle(substr(md5(Configure::read('Security.salt').date("Y-m-d H:i:s")), 0, 10).'-!$#');
                $id = $this->PasswordRecover->id;
                $data = compact('id', 'password');
                fdebug($data);
                $this->User->save($data, false);
                // TODO: send email to user with new password
            }
        }
	}
}
