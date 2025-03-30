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
App::uses('User', 'Model');
App::uses('SiteRouter', 'Lib/Routing');
App::uses('AuthComponent', 'Controller/Component');
class AppController extends Controller {
	public $paginate;
	public $aNavBar = array(), $aBottomLinks = array(), $currMenu = '', $currLink = '', $currUser = false;
	public $pageTitle = '', $aBreadCrumbs = array(), $seo = array(), $disableCopy = true, $leftSidebar = true, $rightSidebar = true;
	public $stylesVersion = 7;

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
    	// $this->set('currUser', $user);
		return Hash::get($user, 'active');
	}

	public function beforeFilter() {
		if ($this->viewPath == 'Errors') {
			$this->layout = 'error-page';
			$this->initNavBar();
			$this->initNavBarView();
			return;
		}
		$this->disableCopy = false; // !TEST_ENV;

		$slug = Configure::read('domain.subdomain');
		if (!in_array($_SERVER['SERVER_PORT'], array('80', '443'))) {
			// avoid any ports except std
			$subdomain = $slug === 'www' ? '' : $slug.'.';
			$this->redirect('https://'.$subdomain.Configure::read('domain.url').$_SERVER['REQUEST_URI']);
		}

		if ($slug <> 'www') {
			$this->loadModel('Category');
			$category = $this->Category->findBySlug($slug);
			if ($category) {
				return $this->redirect($this->getUrl('/zapchasti/'.$slug));
			}

			$this->loadModel('Subdomain');
			$subdomain = $this->Subdomain->findByName($slug);
			if ($subdomain) {
				return $this->redirect($this->getUrl('/filial/'.$slug));
			}

			return $this->redirect404();
			// TODO: redirect on filial if subdomain
		}

		if ($slug = $this->request->param('filial')) {
			$this->loadModel('Subdomain');
			$aSubdomain = $this->Subdomain->findByName($slug);
			if (!$aSubdomain) {
				return $this->redirect404();
			}
			$subdomain = $aSubdomain['Subdomain'];
			Configure::write('domain.filial', $slug);
			Configure::write('domain.subdomain_id', $subdomain['id']);
			Configure::write('Settings.address', nl2br($subdomain['address']));

			$aContacts = array('phone1', 'phone2', 'email', 'skype');
			foreach($aContacts as $type) {
				Configure::write('Settings.'.$type, trim($subdomain[$type]));
			}
		}

		if (isset($this->request->url) && $this->request->url) {
			if (strpos($this->request->url, '.html') !== false) {
				return $this->redirect('/'.str_replace('.html', '', $this->request->url));
			}
			if ($this->request->url !== '/' && substr($this->request->url, -1) == '/' && !$this->request->query) {
				return $this->redirect('/'.substr($this->request->url, 0, -1));
			}
		}

        // always refresh authorized user
        $this->loadModel('User');
        $userID = AuthComponent::user('id');
        if ($userID) {
            $this->currUser = $this->User->findById($userID);
            $this->set('currUser', $this->currUser);
        }

		$this->beforeFilterLayout();
	}

	protected function currUser($key) {
	    return Hash::get($this->currUser, 'User.'.$key);
	}

	protected function getUrl($url, $slug = '') {
		if ($slug) {
			return HTTP.Configure::read('domain.url').'/filial/'.$slug.$url;
		}
		return HTTP.Configure::read('domain.url').$url;
	}

	protected function initNavBar() {
		$this->aNavBar = $this->aBottomLinks = array(
			'home' => array('href' => $this->getUrl('/'), 'title' => __('Home')),
			'news' => array('href' => $this->getUrl('/news', $this->request->param('filial')), 'title' => __('News')),
			'products' => array('href' => $this->getUrl('/zapchasti'), 'title' => __('Spares')),
			'remont' => array('href' => $this->getUrl('/remont'), 'title' => __('Repair')),
			'offer' => array('href' => $this->getUrl('/offers', $this->request->param('filial')), 'title' => __('Hot Offers')),
			'brand' => array('href' => $this->getUrl('/brand'), 'title' => __('Brands')),
			'machinetool' => array('href' => $this->getUrl('/stanki'), 'title' => __('Machine tools')),
			'motor' => array('href' => $this->getUrl('/motors'), 'title' => __('Machinery')),
			'about-us' => array('href' => $this->getUrl('/pages/show/about-us', $this->request->param('filial')), 'title' => ''),
			'dealer' => array('href' => $this->getUrl('/magazini-zapchastei'), 'title' => ''),
			'contacts' => array('href' => $this->getUrl('/contacts', $this->request->param('filial')), 'title' => __('Contacts'))
		);
	}

	protected function beforeFilterLayout() {
		$this->initNavBar();

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

	protected function initNavBarView() {
		$this->set('aNavBar', $this->aNavBar);
		$this->set('currMenu', $this->currMenu);
		$this->set('aBottomLinks', $this->aBottomLinks);
		$this->set('currLink', $this->currLink);
		$this->set('pageTitle', $this->pageTitle);
		$this->set('aBreadCrumbs', $this->aBreadCrumbs);
	}

	public function beforeRender() {
		$this->initNavBarView();
		$this->beforeRenderLayout();
	}

	protected function beforeRenderLayout() {
		$this->set('stylesVersion', $this->stylesVersion);
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
		$this->set('leftSidebar', $this->leftSidebar);
		$this->set('rightSidebar', $this->rightSidebar);

		$this->loadModel('Brand');
		$this->Brand->unbindModel(array('hasOne' => array('Seo')));
		$aBrands = Hash::combine($this->Brand->findAllByPublished(1), '{n}.Brand.id', '{n}');
		$this->set('aBrands', $aBrands);

		$aBrands = Hash::combine($this->Brand->findAllByIsFake(1), '{n}.Brand.id', '{n}');
		$this->set('aFakeBrands', $aBrands);

		/*
		$aCategories = Hash::combine($this->Category->findAllByIsFake(1), '{n}.Category.id', '{n}');
		$this->set('aFakeCategories', $aCategories);
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
		$conditions = array('Category.object_type' => 'Category', 'is_fake' => 0);
		$conditions['Category.export_'.Configure::read('domain.zone')] = 1;
		$order = array('Category.sorting' => 'ASC');
		$aCategories = $this->Category->find('all', compact('conditions', 'order'));
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
		return Configure::Read('domain.subdomain_id');
	}

	protected function getCartItems() {
		return json_decode(isset($_COOKIE['cart']) ? $_COOKIE['cart'] : '{}', true);
	}

	protected function _getCacheFilename($key) {
		return Configure::read('sitemap.dir').Configure::read('sitemap.prefix').$key;
	}

	protected function _cleanCache($key) {
		$fname = $this->_getCacheFilename($key);
		if (Configure::read('sitemap.cache') && file_exists($fname)) {
			return unlink($fname);
		}
		return '';
	}

	protected function getProducts($product_ids) {
	    $this->loadModel('Product');
	    $this->Product->bindModel(array('hasOne' => array('PMFormData' => array(
            'className' => 'Form.PMFormData',
            'foreignKey' => 'object_id',
            'conditions' => array('PMFormData.object_type' => 'ProductParam'),
            'dependent' => true
        ))), false);
        return $this->Product->findAllByIdAndPublished($product_ids, 1);
	}

	protected function getCartProducts() {
	    $cartItems = $this->getCartItems();
        return $this->getProducts(array_keys($cartItems));
	}

	protected function saveSiteOrder($data) {
	    $this->loadModel('SiteOrder');
	    $this->loadModel('SiteOrderDetails');
	    if (!$this->SiteOrder->save($data)) {
	        return false;
	    }

	    $site_order_id = $this->SiteOrder->id;
        foreach($this->getCartItems() as $product_id => $qty) {
            $this->SiteOrderDetails->clear();
            $this->SiteOrderDetails->save(compact('site_order_id', 'product_id', 'qty'));
        }

        $order = $this->SiteOrder->findById($site_order_id);

        $aProducts = $this->getCartProducts();

        $this->loadModel('Brand');
        $this->Brand->unbindModel(array('hasOne' => array('Seo')));
        $aBrands = Hash::combine($this->Brand->findAllById(Hash::extract($aProducts, '{n}.Product.brand_id')), '{n}.Brand.id', '{n}');

        $subject = Configure::read('domain.title') . ': ' . __('New order has been accepted');
        $viewVars = compact('aProducts', 'order', 'cartItems', 'aBrands');

        // create a notify message for Vcars admin
        $View = $this->_getViewObject();
        $body = $View->element('../Emails/html/site_order', $viewVars);
        $this->loadModel('NotifyMessage');
        $this->NotifyMessage->save(array('user_id' => 1, 'title' => $subject, 'body' => $body, 'active' => 1, 'notify_id' => 0));

        if (!TEST_ENV) {
            // send email from site
            $from = 'noreply@' . Configure::read('domain.url');
            $to = Configure::read('Settings.orders_email');
            $emailCfg = array(
                'template' => 'site_order',
                'viewVars' => $viewVars,
                'emailFormat' => 'html',
                'from' => $from,
                'to' => $to,
                'replyTo' => array(Hash::get($data, 'SiteOrder.email') => Hash::get($data, 'SiteOrder.username')),
                'subject' => $subject,
                'bcc' => 'fyr.work@gmail.com'
            );
            $admin_email = Configure::read('Settings.admin_email');
            if ($admin_email && !in_array($admin_email, array($from, $to))) {
                $emailCfg['cc'] = $admin_email;
            }
            $Email = new CakeEmail($emailCfg);
            $Email->send();
        }
        return $site_order_id;
	}
}
