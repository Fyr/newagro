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
			$this->paginate['conditions'][$this->objectType.'.subdomain_id'] = array(SUBDOMAIN_ALL, $this->getSubdomainId());
			$this->paginate['order'] = array(
				$this->objectType.'.subdomain_id' => 'DESC',
				$this->objectType.'.sorting' => 'ASC',
				$this->objectType.'.created' => 'DESC'
			);
		}
		if ($this->objectType == 'Dealer') {
			$this->paginate['limit'] = 100;
		}

		$aArticles = $this->paginate($this->objectType);
		$this->set('aArticles', $aArticles);
		if ($this->objectType == 'News') {
			$ids = Hash::extract($aArticles, '{n}.News.id');
			// exclude featured news from the main news list
			$conditions = array(
				'News.featured' => 1,
				'News.published' => 1,
				'News.subdomain_id' => array(SUBDOMAIN_ALL, $this->getSubdomainId()),
				'News.id NOT' => $ids
			);
			$order = array('News.subdomain_id' => 'DESC', 'News.sorting' => 'ASC', 'News.created' => 'DESC');
			$this->aEvents = $this->News->find('all', compact('fields','conditions', 'order'));
			$this->set('featuredEvents', $this->aEvents);
		}
	}
	
	public function view($slug) {
		$this->loadModel($this->objectType);
		// $method = (in_array($this->objectType, array('News', 'Offer'))) ? 'getBySlug' : 'findBySlug';
		$aArticle = $this->{$this->objectType}->getBySlug($slug);
		
		if (!$aArticle && !TEST_ENV) {
			return $this->redirect404();
		}

		if ($this->objectType == 'News') {
			// check if it is "www" news and redirect on correct link
			if ($this->request->param('filial') && !Hash::get($aArticle, 'News.subdomain_id')) {
				return $this->redirect(array(
					'controller' => 'Articles', 
					'action' => 'view',
					'objectType' => 'News',
					'slug' => $slug
				));
			}
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
