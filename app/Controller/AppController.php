<?php
App::uses('Controller', 'Controller');
App::uses('AppModel', 'Model');
App::uses('Media', 'Media.Model');
App::uses('MediaArticle', 'Model');
App::uses('News', 'Model');
App::uses('Offer', 'Model');
App::uses('Brand', 'Model');
App::uses('Product', 'Model');
App::uses('Category', 'Model');
App::uses('Subcategory', 'Model');
App::uses('SiteRouter', 'Lib/Routing');

class AppController extends Controller {
	public $paginate;
	public $aNavBar = array(), $aBottomLinks = array(), $currMenu = '', $currLink = '';
	public $pageTitle = '', $aBreadCrumbs = array(), $seo = array(), $disableCopy = true;

	public function __construct($request = null, $response = null) {
		$this->_beforeInit();
		parent::__construct($request, $response);
		$this->_afterInit();
	}
	
	protected function _beforeInit() {
	    $this->helpers = array_merge(array('Html', 'Form', 'Paginator', 'ArticleVars', 'Media.PHMedia', 'Core.PHTime', 'Media', 'ObjectType', 'Seo.PHSeo'), $this->helpers);
	}

	protected function _afterInit() {
	    // after construct actions here
		App::uses('Settings', 'Model');

		$this->Settings = new Settings();
		$this->Settings->setDataSource('vitacars'); // load settings from VCars
		$this->Settings->initData();

		$this->Settings = new Settings();
		// $this->Settings->setDataSource('default');
		$this->Settings->initData();
	}
	
	public function isAuthorized($user) {
    	$this->set('currUser', $user);
		return Hash::get($user, 'active');
	}
	
	public function beforeFilter() {
		$this->disableCopy = false; // !TEST_ENV;

		// if (Configure::read('domain.zone') == 'ru') {
			$this->loadModel('Category');
			$this->Category->unbindModel(array('hasOne' => array('Seo')));
			$fields = array('Category.id', 'Category.export_by');
			$conditions = array('slug' => Configure::read('domain.subdomain'), 'is_subdomain' => 1);
			$category = $this->Category->find('first', compact('fields', 'conditions'));
			if ($category) {
				Configure::write('domain.category', Configure::read('domain.subdomain'));
				Configure::write('domain.category_id', Hash::get($category, 'Category.id'));
				Configure::write('domain.subdomain', 'www'); // брать все статьи с www для продуктовых субдоменов
				if (Configure::read('domain.zone') == 'by' && !Hash::get($category, 'Category.export_by')) {
					$this->redirect404();
					return false;
				}
			}

			App::uses('Subdomain', 'Model');
			$this->Subdomain = new Subdomain();
			$subdomain = $this->Subdomain->findByName(Configure::read('domain.subdomain'));
			if (!$subdomain) {
				// $this->redirectNotFound();
				$this->redirect404();
				return false;
			}
			$subdomain = $subdomain['Subdomain'];
			Configure::write('domain.subdomain_id', $subdomain['id']);
			Configure::write('Settings.address', nl2br($subdomain['address']));

			$aContacts = array('phone1', 'phone2', 'email', 'skype');
			foreach($aContacts as $type) {
				Configure::write('Settings.'.$type, trim($subdomain[$type]));
			}
			Configure::write('subdomains', $this->Subdomain->getOptions());

		// }

		if (isset($this->request->url) && $this->request->url) {
			
			if (strpos($this->request->url, '.html') !== false) {
				$this->redirect('/'.str_replace('.html', '', $this->request->url));
				return;
			}
			if ($this->request->url !== '/' && substr($this->request->url, -1) == '/' && !$this->request->query) {
				$this->redirect('/'.substr($this->request->url, 0, -1));
				return;
			}
		}
		$this->beforeFilterLayout();
	}

	protected function getUrl($url) {
		// if (Configure::read('domain.zone') == 'ru') {
			$subdomain = (($subdomain = Configure::read('domain.subdomain')) && $subdomain <> 'www') ? $subdomain . '.' : '';
			return (Configure::read('domain.category'))
				? HTTP . Configure::read('domain.url') . $url
				: HTTP . $subdomain . Configure::read('domain.url') . $url;
		//}
		return HTTP.Configure::read('domain.url').$url;
	}
	
	protected function beforeFilterLayout() {
		$this->aNavBar = $this->aBottomLinks = array(
			'home' => array('href' => $this->getUrl('/'), 'title' => __('Home')),
			'news' => array('href' => $this->getUrl('/news'), 'title' => __('News')),
			'products' => array('href' => $this->getUrl('/zapchasti'), 'title' => __('Spares')),
			'remont' => array('href' => $this->getUrl('/remont'), 'title' => __('Repair')),
			'offer' => array('href' => $this->getUrl('/offers'), 'title' => __('Hot Offers')),
			'brand' => array('href' => $this->getUrl('/brand'), 'title' => __('Brands')),
			'machinetool' => array('href' => $this->getUrl('/stanki'), 'title' => __('Machine tools')),
			'motor' => array('href' => $this->getUrl('/motors'), 'title' => __('Machinery')),
			'about-us' => array('href' => $this->getUrl('/pages/show/about-us'), 'title' => ''),
			'dealer' => array('href' => $this->getUrl('/magazini-zapchastei'), 'title' => ''),
			'contacts' => array('href' => $this->getUrl('/contacts'), 'title' => __('Contacts'))
		);

		$this->currMenu = $this->_getCurrMenu();
	    $this->currLink = $this->currMenu;

		$this->loadModel('News');
		$this->News->unbindModel(array('hasOne' => array('Seo')));
		$conditions = array(
			'News.featured' => 1,
			'News.published' => 1,
			'News.subdomain_id' => array(SUBDOMAIN_ALL, $this->getSubdomainId())
		);
		$order = array('News.subdomain_id' => 'DESC', 'News.sorting' => 'ASC', 'News.created' => 'DESC');
		$this->aEvents = $this->News->find('all', compact('fields','conditions', 'order'));
		$this->set('featuredEvents', $this->aEvents);

		$this->loadModel('Offer');
		$this->Offer->unbindModel(array('hasOne' => array('Seo')));
		$conditions = array(
			'Offer.featured' => 1,
			'Offer.published' => 1,
			'Offer.subdomain_id' => array(SUBDOMAIN_ALL, $this->getSubdomainId())
		);
		$order = array('Offer.sorting' => 'ASC', 'Offer.created' => 'DESC');
		$this->set('featuredOffers', $this->Offer->find('all', compact('conditions', 'order')));
		
		$this->set('aFilters', array());
		$this->set('isHomePage', false);
		$this->set('cartItems', $this->getCartItems());
	}
	
	protected function _getCurrMenu() {
		$curr_menu = strtolower(str_ireplace('Site', '', $this->request->controller)); // By default curr.menu is the same as controller name
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
		$this->pageTitle = ($this->pageTitle) ? $this->pageTitle.' - '.Configure::read('domain.title') : Configure::read('domain.title');
		$this->set('pageTitle', $this->pageTitle);
		
		$this->set('seo', $this->seo); // TODO

		$this->errMsg = (is_array($this->errMsg)) ? implode('<br/>', $this->errMsg) : $this->errMsg;
		if ($this->errMsg) {
			$this->errMsg = '<br/>'.$this->errMsg.'<br/><br/>';
		}
		$this->set('errMsg', $this->errMsg);
		$this->set('aErrFields', $this->aErrFields);
		$this->set('aBreadCrumbs', $this->aBreadCrumbs);
		
		$this->set('disableCopy', $this->disableCopy);
		
		$this->loadModel('Brand');
		$this->Brand->unbindModel(array('hasOne' => array('Seo')));
		$brands = Hash::combine($this->Brand->findAllByPublished(1), '{n}.Brand.id', '{n}');
		$this->set('aBrands', $brands);

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
		if (Configure::read('Settings.sectionizer')) {
			$this->loadModel('Section');
			$aSections = $this->Section->getOptions();
			$this->set('aSections2', $aSections);

			$this->loadModel('SectionArticle');
			$this->SectionArticle->unbindModel(array('hasOne' => array('Seo')));
			$conditions = array('SectionArticle.published' => 1);
			$order = 'SectionArticle.sorting';
			$aArticles = $this->SectionArticle->find('all', compact('conditions', 'order'));

			$aCategories = array();
			$aSubcategories = array();
			foreach($aArticles as $article) {
				$id = $article['SectionArticle']['id'];
				$cat_id = $article['SectionArticle']['cat_id'];
				if ($subcat_id = $article['SectionArticle']['subcat_id']) {
					$aSubcategories[$subcat_id][] = $article;
				} else {
					$aCategories[$cat_id][$id] = $article;
				}
			}
			$this->set('aCategories2', $aCategories);
			$this->set('aSubcategories2', $aSubcategories);
		}
		$this->set('aSections', array(0 => __('Catalog')));

		$this->loadModel('Category');
		$this->Category->unbindModel(array('hasOne' => array('Seo')));
		$conditions = array('Category.object_type' => 'Category', 'is_subdomain' => 1);
		// if (Configure::read('domain.zone') == 'by') {
			$conditions['Category.export_'.Configure::read('domain.zone')] = 1;
		// }
		$order = array('Category.sorting' => 'ASC');
		$aCategories = $this->Category->find('all', compact('conditions', 'order'));
		// $aCategories = $this->Category->findAllByObjectType('Category', array('id', 'title', 'slug', 'is_subdomain', 'Seo.*'), array('Category.sorting' => 'ASC'));
		$aCategories = Hash::combine($aCategories, '{n}.Category.id', '{n}');
		$this->set('aCategories', array(0 => $aCategories));
		foreach ($aCategories as $article) {
			$url = SiteRouter::url($article);
			$this->aNavBar['products']['submenu'][] = array('href' => $url, 'title' => $article['Category']['title']);
		}

		$this->loadModel('Subcategory');
		$this->Subcategory->unbindModel(array('hasOne' => array('Seo')));
		$aSubcategories = $this->Subcategory->getObjectList('Subcategory', '', array('Subcategory.sorting' => 'ASC'));
		$aSubcategories = Hash::combine($aSubcategories, '{n}.Subcategory.id', '{n}', '{n}.Subcategory.object_id');
		$this->set('aSubcategories', $aSubcategories);

		// Fixes for menu titles
		$this->loadModel('Page');
		$aArticleTitles = $this->Page->find('list', array(
			'fields' => array('slug', 'title'),
			'conditions' => array(
				'slug' => array('magazini-zapchastei', 'about-us'),
				'subdomain_id' => 0
			)
		));
		$this->aNavBar['about-us']['title'] = $aArticleTitles['about-us'];
		$this->aBottomLinks['about-us']['title'] = $aArticleTitles['about-us'];
		$this->aNavBar['dealer']['title'] = $aArticleTitles['magazini-zapchastei'];
		$this->aBottomLinks['dealer']['title'] = $aArticleTitles['magazini-zapchastei'];

		$pageEn = $this->Page->findBySlug('en-version');
		$this->set('enPage', ($pageEn) ? $this->getUrl('/pages/show/en-version') : '');
		$this->set('isEN', $pageEn && isset($this->request->pass[0]) && $this->request->pass[0] == 'en-version');

		if (Configure::read('domain.zone') == 'ru') {
			unset($this->aNavBar['home']);
			unset($this->aNavBar['brand']);
			unset($this->aNavBar['machinetool']);
			unset($this->aBottomLinks['brand']);
		} elseif (Configure::read('domain.zone') == 'ua') {
			unset($this->aNavBar['motor']);
			unset($this->aBottomLinks['motor']);
			unset($this->aNavBar['machinetool']);
			unset($this->aBottomLinks['machinetool']);
			unset($this->aNavBar['home']);
			unset($this->aBottomLinks['dealer']);
		} else {
			unset($this->aNavBar['home']);
			unset($this->aNavBar['brand']);
			unset($this->aNavBar['machinetool']);
			unset($this->aBottomLinks['machinetool']);
		}
		$this->set('aBottomLinks', $this->aBottomLinks);
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

		$this->loadModel('Messenger');
		$aMessengers = $this->Messenger->getUsedList();
		$this->set(compact('aMessengers'));
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
		// return $this->redirect(array('controller' => 'pages', 'action' => 'notExists'), 404);
		$this->beforeFilterLayout();
		throw new NotFoundException();
	}

	protected function getSubdomainId() {
		return Configure::read('domain.subdomain_id');
	}

	protected function getCartItems() {
		return json_decode(isset($_COOKIE['cart']) ? $_COOKIE['cart'] : '{}', true);
	}
}
