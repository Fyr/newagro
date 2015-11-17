<?php
App::uses('Controller', 'Controller');
App::uses('AppModel', 'Model');
App::uses('Media', 'Media.Model');
App::uses('MediaArticle', 'Model');
App::uses('News', 'Model');
App::uses('Brand', 'Model');
App::uses('Product', 'Model');
App::uses('CategoryProduct', 'Model');
App::uses('SiteRouter', 'Lib/Routing');

class AppController extends Controller {
	public $paginate;
	public $aNavBar = array(), $aBottomLinks = array(), $currMenu = '', $currLink = '';
	public $pageTitle = '', $aBreadCrumbs = array(), $seo = array();
	
	public function __construct($request = null, $response = null) {
		$this->_beforeInit();
		parent::__construct($request, $response);
		$this->_afterInit();
	}
	
	protected function _beforeInit() {
	    $this->helpers = array_merge(array('Html', 'Form', 'Paginator', 'ArticleVars', 'Media.PHMedia', 'Core.PHTime', 'Media'), $this->helpers);
	}

	protected function _afterInit() {
	    // after construct actions here
	    $this->loadModel('Settings');
	    $this->Settings->initData();
	}
	
	public function isAuthorized($user) {
    	$this->set('currUser', $user);
		return Hash::get($user, 'active');
	}
	
	public function beforeFilter() {
		// parent::beforeFilter();
		$this->beforeFilterLayout();
	}
	
	protected function beforeFilterLayout() {
		$this->aNavBar = array(
			'home' => array('href' => '/', 'title' => 'Главная'),
			'news' => array('href' => '/news', 'title' => 'Новости'),
			'products' => array('href' => '/zapchasti', 'title' => 'Запчасти'),
			'remont' => array('href' => '/pages/show/remont', 'title' => 'Ремонт'),
			'offers' => array('href' => '/offers', 'title' => 'Акции'),
			'brands' => array('href' => '/brand', 'title' => 'Бренды'),
			'motors' => array('href' => '/motors', 'title' => 'Техника'),
			'about-us' => array('href' => '/pages/show/about-us', 'title' => 'О нас'),
			'partner' => array('href' => '/magazini-zapchastei', 'title' => 'Дилеры'),
			'contacts' => array('href' => '/contacts', 'title' => 'Контакты')
		);

		$this->aBottomLinks = array(
			'home' => array('href' => '/', 'title' => 'Главная'),
			'news' => array('href' => '/news', 'title' => 'Новости'),
			'products' => array('href' => '/zaphasti', 'title' => 'Запчасти'),
			'remont' => array('href' => '/pages/show/remont', 'title' => 'Ремонт'),
			'brands' => array('href' => '/brand', 'title' => 'Бренды'),
			'motors' => array('href' => '/motors', 'title' => 'Техника'),
			'about-us' => array('href' => '/pages/show/about-us', 'title' => 'О нас'),
			'partner' => array('href' => '/magazini-zapchastei', 'title' => 'Дилеры'),
			'contacts' => array('href' => '/contacts', 'title' => 'Контакты')
		);

		
		$this->currMenu = $this->_getCurrMenu();
	    $this->currLink = $this->currMenu;
	    
		$this->loadModel('News');
		$this->aEvents = $this->News->getRandomRows(1, array('News.featured' => 1, 'News.published' => 1));
		$this->set('upcomingEvent', ($this->aEvents) ? $this->aEvents[0] : false);
		
		$this->set('aFilters', array());
		$this->set('isHomePage', false);
	}
	
	protected function _getCurrMenu() {
		$curr_menu = strtolower(str_ireplace('Site', '', $this->request->controller)); // By default curr.menu is the same as controller name
		/*
		foreach($this->aNavBar as $currMenu => $item) {
			if (isset($item['submenu'])) {
				foreach($item['submenu'] as $_currMenu => $_item) {
					if (strtolower($_currMenu) === $curr_menu) {
						return $currMenu;
					}
				}
			}
		}
		*/
		return $curr_menu;
	}
	
	public function beforeRender() {
		$this->set('aNavBar', $this->aNavBar);
		$this->set('currMenu', $this->currMenu);
		$this->set('aBottomLinks', $this->aBottomLinks);
		$this->set('currLink', $this->currLink);
		$this->set('pageTitle', $this->pageTitle);
		$this->set('aBreadCrumbs', $this->aBreadCrumbs);
		
		$this->beforeRenderLayout();
	}
	
	protected function beforeRenderLayout() {
		$this->pageTitle = ($this->pageTitle) ? $this->pageTitle.' - '.DOMAIN_TITLE : DOMAIN_TITLE;
		$this->set('pageTitle', $this->pageTitle);
		
		$this->set('seo', $this->seo); // TODO

		$this->errMsg = (is_array($this->errMsg)) ? implode('<br/>', $this->errMsg) : $this->errMsg;
		if ($this->errMsg) {
			$this->errMsg = '<br/>'.$this->errMsg.'<br/><br/>';
		}
		$this->set('errMsg', $this->errMsg);
		$this->set('aErrFields', $this->aErrFields);
		$this->set('aBreadCrumbs', $this->aBreadCrumbs);
		
		$this->set('disableCopy', !TEST_ENV && $this->disableCopy);
		
		if (DOMAIN_NAME == 'agromotors.ru') {
			unset($this->aBottomLinks['motors']);
		}
		$this->set('aBottomLinks', $this->aBottomLinks);

		// $this->Article = $this->SiteArticle;
		$this->loadModel('Brand');
		$brands = $this->Brand->findAllByPublished(1);
		$this->set('aBrandTypes', $brands);
		/*
		$aBrands = array();
		foreach($brands as $article) {
			if (isset($article['Media'][0])) {
				$aBrands[] = $article;
			}
		}
		$this->set('aBrands', $aBrands);
		*/
		
		$aFilter = array();
		if (isset($this->params['url']['data']['filter']['Article.title']) && $this->params['url']['data']['filter']['Article.title']) {
			$aFilter['Article.title'] = $this->params['url']['data']['filter']['Article.title'];
		}
		if (isset($this->params['url']['data']['filter']['Article.object_id']) && $this->params['url']['data']['filter']['Article.object_id']) {
			$aFilter['Article.object_id'] = $this->params['url']['data']['filter']['Article.object_id'];
		}
		if (isset($this->params['url']['data']['filter']['Article.brand_id']) && $this->params['url']['data']['filter']['Article.brand_id']) {
			$aFilter['Article.brand_id'] = $this->params['url']['data']['filter']['Article.brand_id'];
		}
		if (isset($this->params['url']['data']['filter']['Tag.id']) && $this->params['url']['data']['filter']['Tag.id']) {
			$aFilter['Tag.id'] = $this->params['url']['data']['filter']['Tag.id'];
		}
		$this->set('aFilter', $aFilter);

		$this->loadModel('Category');
		$aTypes = $this->Category->getTypesList();
		$this->set('aTypes', $aTypes);

		// Fixes for menu titles
		$this->loadModel('Page');
		$aArticleTitles = $this->Page->find('list', array('fields' => array('slug', 'title'), 'conditions' => array('slug' => array('magazini-zapchastei', 'about-us', 'about-us2', 'contacts1', 'contacts2'))));
		// $this->aNavBar['about']['title'] = $aArticleTitles['about-us'];
		$this->aNavBar['partner']['title'] = $aArticleTitles['magazini-zapchastei'];
		$this->aBottomLinks['partner']['title'] = $aArticleTitles['magazini-zapchastei'];
		$this->set('aBottomLinks', $this->aBottomLinks);
		
		if (DOMAIN_NAME == 'agromotors.by' || TEST_ENV) {
			unset($this->aNavBar['home']);
		} elseif (DOMAIN_NAME == 'agromotors.ru') {
			unset($this->aNavBar['motors']);
		}
		
		foreach($aTypes['type_'] as $type) {
			$url = SiteRouter::catUrl('products', $type);
			$this->aNavBar['products']['submenu'][] = array('href' => $url, 'title' => $type['title']);
		}
		$this->set('aMenu', $this->aNavBar);
		
		$this->loadModel('SlotPlace');
		$this->loadModel('Banner');
		$this->loadModel('BannerType');
		$aSlot = array();
		foreach($this->SlotPlace->getOptions() as $slot_id => $title) {
			$conditions = array('slot' => $slot_id, 'active' => 1);
			$order = 'Banner.sorting';
			$aSlot[$slot_id] = $this->Banner->find('all', compact('conditions', 'order'));
		}
		$this->set('aSlot', $aSlot);
	}
	
	/**
	 * Sets flashing message
	 *
	 * @param str $msg
	 * @param str $type - must be 'success', 'error' or empty
	 */
	protected function setFlash($msg, $type = 'info') {
		$this->Session->setFlash($msg, 'default', array(), $type);
	}

	protected function getObjectType() {
		$objectType = $this->request->param('objectType');
		return ($objectType) ? $objectType : 'SiteArticle';
	}
	
	public function redirect404() {
		return $this->redirect('/404');
	}
}
