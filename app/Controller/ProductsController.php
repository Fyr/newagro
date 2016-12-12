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

class ProductsController extends AppController {
	public $name = 'Products';
	public $uses = array('Category', 'Subcategory', 'Product', 'Seo.Seo', 'Search', 'DetailNum');
	public $components = array('Recaptcha.Recaptcha');
	public $helpers = array('Media.PHMedia', 'Core.PHTime', 'Price');
	
	const PER_PAGE = 51;

	private $searchNumber = '';

	public function index($catSlug = '', $subcatSlug = '') {
		$this->paginate = array(
			'conditions' => array('Product.published' => 1),
			'limit' => self::PER_PAGE, 
			'page' => $this->request->param('page'),
			// 'order' => 'Product.created DESC'
		);
		$category = array();
		if ($catSlug) {
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

		$subcategory = array();
		if ($subcatSlug) {
			// $this->request->data('Subcategory.slug', $subcatSlug);
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
		if (($catSlug && !$category) || ($subcatSlug && !$subcategory)) {
			$slug = ($subcatSlug) ? $subcatSlug : $catSlug;
			$product = $this->Product->findBySlugAndPublished($slug, 1);
			if ($product) {
				return $this->redirect(SiteRouter::url($product));
			} else {
				return $this->redirect404();
			}
		}

		if ($q = $this->request->query('q')) {
			$this->logSearch($q);
			$this->processFilter($q);
			$this->set('directSearch', true);
			if ($this->searchNumber && $this->_filterGpzSearch($this->searchNumber)) {
				try {
					App::uses('GpzApi', 'Model');
					$this->GpzApi = new GpzApi();
					$gpzData = $this->GpzApi->search($q);
					$this->set(compact('gpzData'));
				} catch (Exception $e) {
					$this->set('gpzError', $e->getMessage());
				}
			}
		} else {
			$this->Product->unbindModel(array('hasOne' => array('Search'))); // нужно для поиска по тексту
		}
		if ($data = $this->request->data) {
			$this->paginate['conditions'] = array_merge($this->paginate['conditions'], $this->postConditions($data));
		}

		$this->Product->unbindModel(array('hasOne' => array('Seo')));
		$aProducts = $this->paginate('Product');
		$this->set('aArticles', $aProducts);
		$this->set('objectType', 'Product');
	}

	private function _filterGpzSearch($number) {
		$aBrands = explode(',', Configure::read('Settings.gpz_brands'));
		$this->DetailNum->bindModel(array('belongsTo' => array('Product')));
		$fields = array('DetailNum.detail_num');
		$conditions = array('Product.brand_id' => $aBrands, 'DetailNum.detail_num' => $number);
		$aRows = $this->DetailNum->find('all', compact('conditions', 'fields'));
		return !$aRows;
	}

	public function view($slug) {
		$article = $this->Product->findBySlug($slug);
		if (!$article) {
			return $this->redirect404();
		}
		$id = $article['Product']['id'];
		
		$this->loadModel('MediaArticle');
		$zone = Configure::read('domain.zone');
		$conditions = array('object_type' => 'Product', 'object_id' => $id, 'show_'.$zone => 1);
		$order = array('main_'.$zone => 'DESC', 'id' => 'ASC');
		$aMedia = $this->MediaArticle->find('all', compact('conditions', 'order')); // $this->Media->getList(array('object_type' => 'Product', 'object_id' => $id));
		$article['Media'] = Hash::extract($aMedia, '{n}.MediaArticle');
		
		$this->loadModel('Form.PMFormData');
		$formData = $this->PMFormData->getObject('ProductParam', $id);
		$article['PMFormData'] = Hash::extract($formData, 'PMFormData');
		
		$this->loadModel('Form.PMFormField');
		$conditions = array('PMFormField.object_type' => 'SubcategoryParam', 'exported' => 1);
		$order = 'PMFormField.sort_order ASC';
		$fields = $this->PMFormField->find('all', compact('conditions', 'order'));
		$article['PMFormField'] = Hash::extract($fields, '{n}.PMFormField');
		
		$this->set('article', $article);

		$code = $article['Product']['code'];
		$title_rus = $article['Product']['title_rus'];
		$this->loadModel('SeoArticle');
		$this->seo = $this->SeoArticle->defaultSeo($article['Seo'],
			(Configure::read('domain.zone') == 'ru') ? $code.' '.$title_rus : $title_rus.' '.$code,
			$title_rus.", ".str_replace(',', ' ', $title_rus)." ".$article['Category']['title'].", запчасти для спецтехники ".$article['Category']['title'].", запчасти для ".$article['Category']['title'],
			'На нашем сайте вы можете приобрести '.str_replace(',', ' ', $title_rus).' для трактора или спецтехники '.$article['Category']['title']." в ".((Configure::read('domain.zone') == 'ru') ? 'России' : 'Белоруссии').". Низкие цены на спецтехнику, быстрая доставка по стране, диагностика, ремонт."
		);
		unset($this->seo['keywords']);
		unset($this->seo['descr']);

		$this->set('aRelated', $this->_getRelatedProducts($id, $article['Product']['code'], $article['Product']['cat_id'], $article['Product']['subcat_id']));
	}

	private function _getRelatedProducts($id, $code, $cat_id, $subcat_id) {
		/*
		$limit = 6;
		$value = $this->DetailNum->strip($code);
		$product_ids = $this->DetailNum->findDetails($this->DetailNum->stripList('*'.$value.'*'), true, DetailNum::ORIG);
		if (!$product_ids)  {
			$product_ids = $this->DetailNum->findDetails('*'.$this->DetailNum->stripList($value).'*', true);
		}
		$product_ids = array_diff($product_ids, array($id)); // исключаем текущий продукт
		$products = array();
		if ($product_ids && count($product_ids) >= 6) {
			$conditions = array('Product.id' => $product_ids);
		} else {
			if ($product_ids) { // не хватает продуктов
				$products = $this->Product->findAllById($product_ids);
				// добавляем из той же подкатегории
				$conditions = array('Product.subcat_id' => $subcat_id, 'Product.published' => 1, 'NOT' => array('Product.id' => am($product_ids, array($id))));
				$limit = 6 - count($product_ids);
			} else {
				$conditions = array('Product.subcat_id' => $subcat_id, 'Product.published' => 1, 'Product.id <> ' => $id);
			}
		}
		$products = am($products, $this->Product->find('all', compact('conditions', 'limit')));
		*/

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
			$order[] = 'Product.id = ' . $id . ' DESC';
		}
		$this->paginate['order'] = implode(', ', $order);
	}

	public function price() {
		$number = $this->request->query('number');
		$brand = $this->request->query('brand');

		$aSorting = array(
			'brand' => 'Производитель',
			'partnumber' => 'Номер',
			'image' => 'Фото',
			'title' => 'Наименование',
			'qty' => 'Наличие',
			// 'price2' => 'Цена'
		);
		$this->set('aSorting', $aSorting);
		$aOrdering = array(
			'asc' => 'по возрастанию',
			'desc' => 'по убыванию'
		);
		$this->set('aOrdering', $aOrdering);

		$sort = $this->request->query('sort');
		if (!$sort || !in_array($sort, array_keys($aSorting))) {
			$sort = 'brand';
		}
		$order = $this->request->query('order');
		if (!$order || !in_array($order, array_keys($aOrdering))) {
			$order = 'asc';
		}
		$this->set('sort', $sort);
		$this->set('order', $order);

		$lFullInfo = false;
		try {
			App::uses('GpzApi', 'Model');
			$this->GpzApi = new GpzApi();
			$gpzData = $this->GpzApi->getPrices($brand, $number, $sort, $order, $lFullInfo);

			$title = $number.' '.$brand;
			/*
			foreach($gpzData as $row) {
				if ($row['title'] != '(БЕЗ НАЗВАНИЯ)' && $row['title']) {
					$title = $row['title'];
					break;
				}
			}
			*/
			$this->set(compact('gpzData', 'lFullInfo', 'title'));
			$this->set('aOfferTypeOptions', GpzOffer::options());
		}  catch (Exception $e){
			$this->set('gpzError', $e->getMessage());
		}
	}

}
