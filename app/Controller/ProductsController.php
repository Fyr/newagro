<?php
App::uses('AppController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('Media', 'Media.Model');
App::uses('PMFormData', 'Form.Model');
App::uses('Product', 'Model');
App::uses('Category', 'Model');
App::uses('Subcategory', 'Model');
App::uses('PHTimeHelper', 'Core.View/Helper');

class ProductsController extends AppController {
	public $name = 'Products';
	public $uses = array('Category', 'Subcategory', 'Product');
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
			$this->set('category', $this->Category->findBySlug($catSlug));
		}
		if ($subcatSlug) {
			$this->request->data('Subcategory.slug', $subcatSlug);
			$this->set('subcategory', $this->Subcategory->findBySlug($subcatSlug));
		}
		if ($q = $this->request->query('q')) {
			$this->processFilter($q);
		}
		if ($data = $this->request->data) {
			$this->paginate['conditions'] = array_merge($this->paginate['conditions'], $this->postConditions($data));
		}
		$aProducts = $this->paginate('Product');
		$this->set('aArticles', $aProducts);
		$this->set('objectType', 'Product');
	}
	
	public function view($slug) {
		$Product = $this->Product->findBySlug($slug);
		if (!$Product) {
			return $this->redirect404();
		}
		$id = $Product['Product']['id'];
		
		$this->loadModel('MediaProduct');
		$aMedia = $this->MediaProduct->getObjectList('Product', $id); // $this->Media->getList(array('object_type' => 'Product', 'object_id' => $id));
		$Product['Media'] = Hash::extract($aMedia, '{n}.MediaProduct');
		
		$this->loadModel('Form.PMFormData');
		$formData = $this->PMFormData->getObject('ProductParam', $id);
		$Product['PMFormData'] = Hash::extract($formData, 'PMFormData');
		
		$this->loadModel('Form.PMFormField');
		$conditions = array('PMFormField.object_type' => 'SubcategoryParam', 'exported' => 1);
		$order = 'PMFormField.sort_order ASC';
		$fields = $this->PMFormField->find('all', compact('conditions', 'order'));
		$Product['PMFormField'] = Hash::extract($fields, '{n}.PMFormField');
		$this->set('Product', $Product);
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
