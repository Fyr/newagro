<?php
App::uses('AppController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('Media', 'Media.Model');
App::uses('PMFormData', 'Form.Model');
App::uses('Seo', 'Seo.Model');
App::uses('Product', 'Model');
App::uses('Category', 'Model');
App::uses('Subcategory', 'Model');
App::uses('PHTimeHelper', 'Core.View/Helper');

class ProductsController extends AppController {
	public $name = 'Products';
	public $uses = array('Category', 'Subcategory', 'Product', 'Seo.Seo');
	public $components = array('Recaptcha.Recaptcha');
	// public $helpers = array('Media.PHMedia', 'Core.PHTime', 'Recaptcha.Recaptcha');
	
	const PER_PAGE = 51;
	
	public function index($catSlug = '', $subcatSlug = '') {
		$this->paginate = array(
			'conditions' => array('Product.published' => 1),
			'limit' => self::PER_PAGE, 
			'page' => $this->request->param('page'),
			'order' => 'Product.created DESC'
		);
		if ($catSlug) {
			$this->request->data('Category.slug', $catSlug);
			$category = $this->Category->findBySlug($catSlug);
			$this->set('category', $category);
			
			$page_title = $category['Category']['title'];
			$this->seo = $this->Seo->defaultSeo($category['Seo'],
				'Каталог продукции '.$page_title,
				"каталог продукции {$page_title}, запчасти для тракторов {$page_title}, запчасти для спецтехники {$page_title}, запчасти для {$page_title}",
				"На нашем сайте вы можете приобрести лучшие запчасти {$page_title} в Белорусии. Низкие цены на спецтехнику, быстрая доставка по стране, диагностика, ремонт."
			);
		}
		if ($subcatSlug) {
			$this->request->data('Subcategory.slug', $subcatSlug);
			$subcategory = $this->Subcategory->findBySlug($subcatSlug);
			$this->set('subcategory', $subcategory);
			
			$page_title = $subcategory['Subcategory']['title'];
			$this->seo = $this->Seo->defaultSeo($subcategory['Seo'],
				'Каталог продукции '.$page_title,
				"каталог продукции {$page_title}, запчасти для тракторов {$page_title}, запчасти для спецтехники {$page_title}, запчасти для {$page_title}",
				"На нашем сайте вы можете приобрести лучшие запчасти {$page_title} в Белорусии. Низкие цены на спецтехнику, быстрая доставка по стране, диагностика, ремонт."
			);
		}
		if ($q = $this->request->query('q')) {
			$this->processFilter($q);
			$this->set('directSearch', true);
		}
		if ($data = $this->request->data) {
			$this->paginate['conditions'] = array_merge($this->paginate['conditions'], $this->postConditions($data));
		}
		$aProducts = $this->paginate('Product');
		$this->set('aArticles', $aProducts);
		$this->set('objectType', 'Product');
	}
	
	public function view($slug) {
		$article = $this->Product->findBySlug($slug);
		if (!$article) {
			return $this->redirect404();
		}
		$id = $article['Product']['id'];
		
		$this->loadModel('MediaArticle');
		$aMedia = $this->MediaArticle->getObjectList('Product', $id); // $this->Media->getList(array('object_type' => 'Product', 'object_id' => $id));
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
		
		$this->seo = $this->Seo->defaultSeo($article['Seo'],
			$article['Product']['title_rus'],
			$article['Product']['title_rus'].", ".str_replace(',', ' ', $article['Product']['title_rus'])." ".$article['Category']['title'].", запчасти для спецтехники ".$article['Category']['title'].", запчасти для ".$article['Category']['title'],
			'На нашем сайте вы можете приобрести '.str_replace(',', ' ', $article['Product']['title_rus']).' для трактора или спецтехники '.$article['Category']['title'].' в Белорусии. Низкие цены на спецтехнику, быстрая доставка по стране, диагностика, ремонт.'
		);
		unset($this->seo['keywords']);
		unset($this->seo['descr']);
	}
	
	private function processFilter($value) {
		$_value = str_replace(array('.', ' ', '-', ',', '/', '\\'), '', $value);
		
		// ищем запчасти по "марка TC, моторы TC, доп.инфа"
		$this->loadModel('Form.PMFormData');
		$conditions = array();
		foreach(array(Configure::read('params.markaTS'), Configure::read('params.motorsTS'), Configure::read('params.dopInfa')) as $id) {
			$conditions[] = "(PMFormData.fk_{$id} LIKE '%{$value}%' OR PMFormData.fk_{$id} LIKE '%{$_value}%')";
		}
		$conditions = implode(' AND ', $conditions);
		$products = $this->PMFormData->find('all', compact('conditions'));
		$product_ids = implode(',', ($products) ? Hash::extract($products, '{n}.PMFormData.object_id') : array(0));
		
		// поиск по общим номера деталей
		$numbers = explode(' ', str_replace(',', ' ', $_value));
		$ors = array();
		$order = array();
		$i = 0;
		$count = count($numbers);
		$_count = 0;
		while ($i < 100 && $count !== $_count) {
			$i++; // избегать бесконечный цикл
			foreach ($numbers as $key_ => $value_) {
				if (trim($value_) != ''){
					$ors[] = array('Product.detail_num LIKE "%'.trim($value_).'%"');
					$order[] = 'Product.detail_num LIKE "%'.trim($value_).'%" DESC';
				}
			}
			$products = $this->Product->find('all', array('conditions' => array('OR' => $ors)));
			foreach($products as $product) {
				$numbers = array_merge($numbers, explode(' ', str_replace(',', ' ', $product['Product']['detail_num'])));
			}
			$numbers = array_unique($numbers);
			$_count = $count;
			$count = count($numbers);
		}
		
		$ors = array(
			"Product.title LIKE '%{$value}%'", "Product.title LIKE '%{$_value}%'",
			"Product.title_rus LIKE '%{$value}%'", "Product.title_rus LIKE '%{$_value}%'",
			"Product.detail_num LIKE '%{$value}%'", "Product.detail_num LIKE '%{$_value}%'",
			"Product.id IN ({$product_ids})"
		);
		foreach ($numbers as $key_ => $value_) {
			if (trim($value_) != ''){
				$ors[] = 'Product.detail_num LIKE "%'.trim($value_).'%"';
			}
		}
		
		$this->paginate['conditions'][] = '('.implode(' OR ', $ors).')';
	}
}
