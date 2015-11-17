<?php
App::uses('AppController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('SiteArticle', 'Model');
App::uses('News', 'Model');
class ArticlesController extends AppController {
	public $name = 'Articles';
	public $uses = array('SiteArticle', 'News');
	public $helpers = array('ObjectType');
	
	const PER_PAGE = 20;
	
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
		$this->paginate = array(
			'conditions' => array($this->objectType.'.published' => 1),
			'limit' => self::PER_PAGE, 
			'order' => $this->objectType.'.created DESC',
			'page' => $this->request->param('page')
		);
		$this->set('aArticles', $this->paginate($this->objectType));
	}
	
	public function view($slug) {
		$aArticle = $this->{$this->objectType}->findBySlug($slug);
		
		if (!$aArticle && !TEST_ENV) {
			return $this->redirect404();
		}
		
		$this->set('article', $aArticle);
		
		if (!(isset($aArticle['Seo']) & isset($aArticle['Seo']['title']) && $aArticle['Seo']['title'])) {
			$aArticle['Seo']['title'] = $aArticle[$this->objectType]['title'];
		}
		$this->seo = $aArticle['Seo'];
		$this->currMenu = $slug;
	}
}
