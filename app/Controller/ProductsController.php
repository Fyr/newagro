<?php
App::uses('AppController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('Media', 'Media.Model');
App::uses('PMFormData', 'Form.Model');
App::uses('Seo', 'Seo.Model');
App::uses('Product', 'Model');
App::uses('Category', 'Model');
App::uses('Subcategory', 'Model');
App::uses('Search', 'Model');
App::uses('DetailNum', 'Model');
App::uses('UserBrandDiscount', 'Model');
App::uses('SiteRouter', 'Lib/Routing');
App::uses('PHTimeHelper', 'Core.View/Helper');
App::uses('CakeEmail', 'Network/Email');
App::uses('PriceHelper', 'View/Helper');
class ProductsController extends AppController {
	public $name = 'Products';
	public $uses = array('Category', 'Subcategory', 'Product', 'Seo.Seo', 'Search', 'DetailNum', 'UserBrandDiscount');
	public $components = array('Recaptcha.Recaptcha');
	public $helpers = array('Media.PHMedia', 'Core.PHTime', 'Price', 'Form.PHForm', 'Price');

	const PER_PAGE = 51;

	private $searchNumber = '';

	public function index($catSlug = '', $subcatSlug = '') {
		$zone = Configure::read('domain.zone');
		$this->paginate = array(
			'conditions' => array('Product.published' => 1),
			'limit' => self::PER_PAGE,
			'page' => $this->request->param('page'),
			'order' => "MediaArticle.main_$zone DESC"
		);
		$q = $this->request->query('q');
		$catSlug = $this->request->param('category');
		$category = array();
		if ($catSlug && !$q) {
			$category = $this->Category->findBySlug($catSlug);
			if (!$category || !Hash::get($category, "Category.export_$zone")) {
				return $this->redirect404();
			}
			$this->request->data('Product.cat_id', $category['Category']['id']);
				$this->set('category', $category);
				$page_title = $category['Category']['title'];
				$this->seo = $this->Seo->defaultSeo($category['Seo'],
					'Каталог продукции '.$page_title,
					"каталог продукции {$page_title}, запчасти для тракторов {$page_title}, запчасти для спецтехники {$page_title}, запчасти для {$page_title}",
					"На нашем сайте вы можете приобрести лучшие запчасти {$page_title} в ".((Configure::read('domain.zone') == 'ru') ? 'России' : 'Белоруссии').". Низкие цены на спецтехнику, быстрая доставка по стране, диагностика, ремонт."
				);
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
			} else {
				return $this->redirect404();
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
			'hasOne' => array('Seo')
		), false); // need permanent unbind for COUNT(*) query

		$this->paginate['fields'] = array('Product.id');
		$aProducts = $this->paginate('Product');

		$this->Product->bindModel(compact('belongsTo', 'hasOne'), false);
		$this->Product->bindModel(array('hasOne' => array('PMFormData' => array(
			'className' => 'Form.PMFormData',
			'foreignKey' => 'object_id',
			'conditions' => array('PMFormData.object_type' => 'ProductParam'),
			'dependent' => true
		))), false);
		$product_ids = Hash::extract($aProducts, '{n}.Product.id');
		$conditions = array('Product.id' => $product_ids);
		$order = array("MediaArticle.main_$zone DESC");
		// $order = array();
		if ($q) {
			// keep order of search relevant
			foreach ($product_ids as $id) {
				$order[] = 'Product.id = '.$id.' DESC';
			}
			$order = implode(', ', $order);
		}
		$aProducts = $this->Product->find('all', compact('conditions', 'order'));

		$this->set('aArticles', $aProducts);
		$this->set('objectType', 'Product');
	}

	public function view($slug) {
		$catSlug = $this->request->param('category');
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

        $aRelated = $this->_getRelatedProducts($id, $article['Product']['code'], $article['Product']['cat_id'], $article['Product']['subcat_id']);
		$aDiscounts = array();
		if ($this->currUser('id')) {
		    $aDiscounts = $this->UserBrandDiscount->find('list', array(
                'fields' => array('brand_id', 'discount'),
                'conditions' => array('client_id' => $this->currUser('id'))
            ));
        }
        $this->set(compact('aRelated', 'aDiscounts'));
	}

	private function _getRelatedProducts($id, $code, $cat_id, $subcat_id) {
		$zone = Configure::read('domain.zone');

		// find products with photos
		$this->Product->unbindModel(array(
			'belongsTo' => array('Category', 'Subcategory'),
			'hasOne' => array('Seo', 'Search', 'PMFormData')
		), true);
		$conditions = array('Product.published' => 1);
		$conditions['Product.subcat_id'] = $subcat_id;
		$conditions['Product.cat_id'] = $cat_id;
		$conditions["Product.code !="] = $code;
		$conditions["MediaArticle.main_$zone"] = 1;

		$fields = array('id');
		$products = $this->Product->find('all', compact('fields', 'conditions'));
		$ids = Hash::extract($products, '{n}.Product.id');

		// find random products
		$conditions = array('Product.id' => $ids);
		$aProducts = $this->Product->getRandomRows(6, $conditions);
		return $aProducts;
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
	    if ($this->currUser) {
	        return $this->redirect(array('controller' => 'user', 'action' => 'cart'));
	    }
		if ($this->request->is(array('put', 'post'))) {
		    $this->request->data('SiteOrder.zone', Configure::read('domain.zone'));

		    $site_order_id = $this->saveSiteOrder($this->request->data);
			if ($site_order_id) {
				return $this->redirect(array('controller' => 'products', 'action' => 'success', $site_order_id));
			}
		}

		$this->set(array(
		    'aProducts' => $this->getCartProducts(),
		    'aDiscounts' => array()
        ));
		$this->disableCopy = false;
	}

	public function success($id) {
		$this->loadModel('SiteOrder');
		$this->set('order', $this->SiteOrder->findById($id));
		$this->set('cartItems', array());
		$this->disableCopy = false;
	}
}
