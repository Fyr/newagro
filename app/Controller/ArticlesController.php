<?php
App::uses('AppController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('SiteArticle', 'Model');
App::uses('News', 'Model');
App::uses('Offer', 'Model');
App::uses('Motor', 'Model');
App::uses('Brand', 'Model');
App::uses('SiteArticle', 'Model');
class ArticlesController extends AppController {
	public $name = 'Articles';
	public $components = array('Table.PCTableGrid');
	// public $uses = array('News', 'Offer', 'Motor');
	
	// const PER_PAGE = 3;
	const PER_PAGE = 21;
	
	protected $objectType;

	public function beforeFilter() {
		$this->objectType = $this->getObjectType();
		
		parent::beforeFilter();
	}
	
	public function beforeRender() {
		$this->currMenu = strtolower($this->objectType);
		$this->set('objectType', $this->objectType);
		
		parent::beforeRender();
	}
	
	public function index() {
		$this->loadModel($this->objectType);
		$this->paginate = array(
			'conditions' => array(
				$this->objectType.'.published' => 1,
			),
			'limit' => self::PER_PAGE,
			'order' => array(
				$this->objectType.'.sorting' => 'ASC',
				$this->objectType.'.created' => 'DESC'
			),
			'page' => $this->request->param('page')
		);
		if (in_array($this->objectType, array('News', 'Offer'))) {
			$this->paginate['conditions'][$this->objectType.'.subdomain_id'] = array(SUBDOMAIN_ALL, $this->getSubdomainId(), SUBDOMAIN_WWW);
			$this->paginate['order'] = array(
				$this->objectType.'.subdomain_id' => 'DESC',
				$this->objectType.'.sorting' => 'ASC',
				$this->objectType.'.created' => 'DESC'
			);
		}
		if ($this->objectType == 'Dealer') {
			$this->paginate['limit'] = 100;
		}
		$this->set('aArticles', $this->paginate($this->objectType));
	}
	
	public function view($slug) {
		$this->loadModel($this->objectType);
		// $method = (in_array($this->objectType, array('News', 'Offer'))) ? 'getBySlug' : 'findBySlug';
		$aArticle = $this->{$this->objectType}->getBySlug($slug);
		
		if (!$aArticle && !TEST_ENV) {
			return $this->redirect404();
		}
		
		$this->set('article', $aArticle);
		
		if (!(isset($aArticle['Seo']) & isset($aArticle['Seo']['title']) && $aArticle['Seo']['title'])) {
			$aArticle['Seo']['title'] = $aArticle[$this->objectType]['title'];
		}
		$this->seo = $aArticle['Seo'];
		$this->currMenu = $slug;

		if ($this->objectType == 'SectionArticle') {
			if ($aArticle['SectionArticle']['subcat_id']) {
				$this->set('category', $this->SectionArticle->findById($aArticle['SectionArticle']['subcat_id']));
				$this->set('currSubcat', $aArticle['SectionArticle']['id']);
			} else {
				$this->set('category', $aArticle);
			}
		}
	}
}
