<?php
App::uses('AppController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('Media', 'Media.Model');
App::uses('PMFormData', 'Form.Model');
App::uses('Seo', 'Seo.Model');
App::uses('Product', 'Model');
App::uses('Category', 'Model');
App::uses('Subcategory', 'Model');
App::uses('SiteRouter', 'Lib/Routing');
App::uses('PHTimeHelper', 'Core.View/Helper');
App::uses('CakeEmail', 'Network/Email');
App::uses('PriceHelper', 'View/Helper');
class ProductsController extends AppController {
	public $name = 'Products';
	public $uses = array('Category', 'Subcategory', 'Product', 'Seo.Seo', 'Search', 'DetailNum');
	public $components = array('Recaptcha.Recaptcha');
	public $helpers = array('Media.PHMedia', 'Core.PHTime', 'Price', 'Form.PHForm', 'Price');
	
	const PER_PAGE = 51;

	private $searchNumber = '';

	/*
	public function index($subcatSlug = '') {
		$this->products_index($this->Category->findBySlug($catSlug), $subcatSlug);
	}
*/
	/*
	public function subdomain_index($subcatSlug = '') {
		$cat_id = Configure::read('domain.category_id');
		$this->products_index($this->Category->findById($cat_id), $subcatSlug);
	}
*/
	public function index() {
		if (!Configure::read('domain.category') && strpos($this->request->here, '/zapchasti') === 0) {
			// в URL есть /zapchasti (роут с субдоменом), но нет субдомена
			$conditions = array('slug' => $this->request->param('subcategory'), 'is_subdomain' => 1); // согласно роута передали подкатегорию
			$_category = $this->Category->find('first', compact('conditions'));
			if ($_category) {
				// найдена категория с субдоменом по адресу /zapchasti/subdomain-cat - возможно просто старый URL из индекса поисковика
				return $this->redirect(SiteRouter::url($_category));
			}
			return $this->redirect404();
		}

		if (Configure::read('domain.category') && strpos($this->request->here, '/autozapchasti') === 0) {
			// был баг с битыми урлами в постраничке
			return $this->redirect(str_replace('/autozapchasti', '/zapchasti', $this->request->here));
		}

		$this->paginate = array(
			'conditions' => array('Product.published' => 1),
			'limit' => self::PER_PAGE, 
			'page' => $this->request->param('page'),
			// 'order' => 'Product.created DESC'
		);
		$q = $this->request->query('q');
		if (Configure::read('domain.category_id')) {
			$catSlug = Configure::read('domain.category');
		} else {
			$catSlug = $this->request->param('category');
		}
		$category = array();
		if ($catSlug && !$q) {
			$category = $this->Category->findBySlug($catSlug);
			if ($category) {
				$this->request->data('Product.cat_id', $category['Category']['id']);
				$this->set('category', $category);
				$page_title = $category['Category']['title'];
				$this->seo = $this->Seo->defaultSeo($category['Seo'],
					'Каталог продукции '.$page_title,
					"каталог продукции {$page_title}, запчасти для тракторов {$page_title}, запчасти для спецтехники {$page_title}, запчасти для {$page_title}",
					"На нашем сайте вы можете приобрести лучшие запчасти {$page_title} в ".((Configure::read('domain.zone') == 'ru') ? 'России' : 'Белоруссии').". Низкие цены на спецтехнику, быстрая доставка по стране, диагностика, ремонт."
				);
			}
		}

		$subcatSlug = $this->request->param('subcategory');
		$subcategory = array();
		if ($subcatSlug && !$q) {
			$subcategory = $this->Subcategory->findBySlug($subcatSlug);
			if ($subcategory) {
				$this->request->data('Product.subcat_id', $subcategory['Subcategory']['id']);
				$this->set('subcategory', $subcategory);
				$this->set('currSubcat', $subcategory['Subcategory']['id']);
				$page_title = $subcategory['Subcategory']['title'];
				$this->seo = $this->Seo->defaultSeo($subcategory['Seo'],
					'Каталог продукции ' . $page_title,
					"каталог продукции {$page_title}, запчасти для тракторов {$page_title}, запчасти для спецтехники {$page_title}, запчасти для {$page_title}",
					"На нашем сайте вы можете приобрести лучшие запчасти {$page_title} в ".((Configure::read('domain.zone') == 'ru') ? 'России' : 'Белоруссии').". Низкие цены на спецтехнику, быстрая доставка по стране, диагностика, ремонт."
				);
			}
		}
		// редирект если для продукта есть только категория, значит второй slug - это slug продукта
		if (($catSlug && !$category) || ($subcatSlug && !$subcategory)) {
			$slug = ($subcatSlug) ? $subcatSlug : $catSlug;
			$product = $this->Product->findBySlugAndPublished($slug, 1);
			if ($product) {
				return $this->redirect(SiteRouter::url($product));
			} elseif (!$q) {
				return $this->redirect404();
			}
		}

		if ($q) {
			$this->logSearch($q);
			$this->processFilter($q);
			$this->set('directSearch', true);
		} else {
			$this->Product->unbindModel(array('hasOne' => array('Search'))); // нужно для поиска по тексту
		}
		if ($data = $this->request->data) {
			$this->paginate['conditions'] = array_merge($this->paginate['conditions'], $this->postConditions($data));
		}

		// remove associations with all models
		// get product IDs then link it again
		$belongsTo = $this->Product->belongsTo;
		$hasOne = $this->Product->hasOne;
		$this->Product->unbindModel(array(
			'belongsTo' => array('Category', 'Subcategory'),
			'hasOne' => array('MediaArticle', 'Seo', 'Search')
		), false); // need permanent unbind for COUNT(*) query

		$this->paginate['fields'] = array('Product.id');
		$aProducts = $this->paginate('Product');

		$this->Product->bindModel(compact('belongsTo', 'hasOne'), false);

		$product_ids = Hash::extract($aProducts, '{n}.Product.id');
		$conditions = array('Product.id' => $product_ids);
		$order = array();
		foreach ($product_ids as $id) {
			$order[] = 'Product.id = '.$id.' DESC';
		}
		$order = implode(', ', $order);
		$aProducts = $this->Product->find('all', compact('conditions', 'order'));

		$this->set('aArticles', $aProducts);
		$this->set('objectType', 'Product');
	}

	public function view($slug) {
		if (Configure::read('domain.category_id')) {
			$catSlug = Configure::read('domain.category');
		} else {
			$catSlug = $this->request->param('category');
		}
		$conditions = array('Product.slug' => $slug, 'Category.slug' => $catSlug, 'Product.published' => 1); // prevent equal slugs
		$this->Product->bindModel(array('hasOne' => array('PMFormData' => array(
			'className' => 'Form.PMFormData',
			'foreignKey' => 'object_id',
			'conditions' => array('PMFormData.object_type' => 'ProductParam'),
			'dependent' => true
		))), false);
		$article = $this->Product->find('first', compact('conditions'));
		if (!$article) {
			return $this->redirect404();
		}
		$id = $article['Product']['id'];
		
		$this->loadModel('MediaArticle');
		$zone = Configure::read('domain.zone');
		$conditions = array('object_type' => 'Product', 'object_id' => $id, 'show_'.$zone => 1);
		$order = array('main_'.$zone => 'DESC', 'id' => 'ASC');
		$limit = 50; // max - 50 media for detail
		if ($article['Product']['is_fake'] && $article['Product']['orig_id']) {
			$conditions['object_id'] = $article['Product']['orig_id'];
			$order = array('main_'.$zone => 'ASC', 'id' => 'DESC');
			$limit = 1;
		}
		$aMedia = $this->MediaArticle->find('all', compact('conditions', 'order', 'limit')); // $this->Media->getList(array('object_type' => 'Product', 'object_id' => $id));
		$article['Media'] = Hash::extract($aMedia, '{n}.MediaArticle');

		/*
		$this->loadModel('Form.PMFormData');
		$formData = $this->PMFormData->getObject('ProductParam', $id);
		$article['PMFormData'] = Hash::extract($formData, 'PMFormData');
		*/
		$this->loadModel('Form.PMFormField');
		$conditions = array('PMFormField.object_type' => 'SubcategoryParam', 'exported' => 1);
		$order = 'PMFormField.sort_order ASC';
		$fields = $this->PMFormField->find('all', compact('conditions', 'order'));
		$article['PMFormField'] = Hash::extract($fields, '{n}.PMFormField');

		$this->set('article', $article);
		$this->set('category', array('Category' => $article['Category']));
		$this->set('currSubcat', $article['Subcategory']['id']);

		$code = $article['Product']['code'];
		$title_rus = $article['Product']['title_rus'];
		$this->loadModel('SeoArticle');
		$this->seo = $this->SeoArticle->defaultSeo($article['Seo'],
			(Configure::read('domain.zone') == 'ru') ? $code.' '.$title_rus : $title_rus.' '.$code,
			$title_rus.", ".str_replace(',', ' ', $title_rus)." ".$article['Category']['title'].", запчасти для спецтехники ".$article['Category']['title'].", запчасти для ".$article['Category']['title'],
			'На нашем сайте вы можете приобрести '.str_replace(',', ' ', $title_rus).' для трактора или спецтехники '.$article['Category']['title']." в ".((Configure::read('domain.zone') == 'ru') ? 'России' : 'Белоруссии').". Низкие цены на спецтехнику, быстрая доставка по стране, диагностика, ремонт."
		);
		// unset($this->seo['keywords']);
		// unset($this->seo['descr']);

		$this->set('aRelated', $this->_getRelatedProducts($id, $article['Product']['code'], $article['Product']['cat_id'], $article['Product']['subcat_id']));
	}

	private function _getRelatedProducts($id, $code, $cat_id, $subcat_id) {
		$conditions = array('Product.published' => 1, 'Product.code <> ' => $code);
		if ($subcat_id) {
			$conditions['Product.subcat_id'] = $subcat_id;
		} else {
			$conditions['Product.cat_id'] = $cat_id;
		}
		return $this->Product->getRandomRows(6, $conditions);
	}

	private function logSearch($q) {
		$this->loadModel('SearchLog');
		$this->SearchLog->save(array('q' => $q, 'ip' => $_SERVER['REMOTE_ADDR']));
	}

	private function processFilter($value) {
		// очищаем от лишних пробелов
		$_value = $this->Search->stripSpaces(mb_strtolower($value));

		// если ввели только номер - поиск по номерам
		$aWords = explode(' ', $_value);
		if (count($aWords) == 1 && $this->DetailNum->isDigitWord($value)) {
			$this->processNumber($this->DetailNum->strip($value));
			return;
		}
		$aWords = $this->Search->processTextRequest($_value);
		$this->paginate['conditions']['Search.body LIKE '] = '%'.implode('%', $aWords).'%';
		if ($this->Search->isRu($_value)) {
			$this->paginate['order'] = 'Product.title_rus LIKE "'.$_value.'%" DESC';
		} else {
			$this->paginate['order'] = 'Product.title LIKE "'.$_value.'%" DESC';
		}
	}

	private function processNumber($value) {
		$this->searchNumber = $value;
		// $_value = str_replace(array('.', '-', ',', '/', '\\'), '', $value);
		$product_ids = $this->DetailNum->findDetails($this->DetailNum->stripList('*'.$value.'*'), true, DetailNum::ORIG);
		$this->paginate['conditions'] = array('Product.id' => $product_ids);
		$order = array();
		foreach ($product_ids as $id) {
			$order[] = 'Product.id = '.$id.' DESC';
		}
		$this->paginate['order'] = implode(', ', $order);
	}

	public function cart() {
		$this->loadModel('SiteOrder');
		$this->loadModel('SiteOrderDetails');

		$cartItems = $this->getCartItems();
		$this->Product->bindModel(array('hasOne' => array('PMFormData' => array(
			'className' => 'Form.PMFormData',
			'foreignKey' => 'object_id',
			'conditions' => array('PMFormData.object_type' => 'ProductParam'),
			'dependent' => true
		))), false);
		$aProducts = $this->Product->findAllByIdAndPublished(array_keys($cartItems), 1);

		if ($this->request->is(array('put', 'post'))) {
			if ($this->SiteOrder->save($this->request->data)) {
				$site_order_id = $this->SiteOrder->id;
				foreach($this->getCartItems() as $product_id => $qty) {
					$this->SiteOrderDetails->clear();
					$this->SiteOrderDetails->save(compact('site_order_id', 'product_id', 'qty'));
				}

				$order = $this->SiteOrder->findById($site_order_id);

				$cartItems = $this->getCartItems();

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
						'replyTo' => array($this->request->data('SiteOrder.email') => $this->request->data('SiteOrder.username')),
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
				$this->redirect(HTTP.Configure::read('domain.url').Router::url(array('action' => 'success', $site_order_id)));
			}
		}

		$this->set(compact('aProducts'));
		$this->disableCopy = false;
	}

	public function success($id) {
		$this->loadModel('SiteOrder');
		$this->set('order', $this->SiteOrder->findById($id));
		$this->set('cartItems', array());
		$this->disableCopy = false;
	}
}
