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
	/*
	public function beforeFilter() {
		$this->objectType = $this->getObjectType();
		$this->set('objectType', $this->objectType);
		parent::beforeFilter();
	}
	*/
	
	/*
	public function beforeRender() {
		$this->currMenu = 'Products';
		
		parent::beforeRender();
	}
	*/
	
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
		
		/*
		$aMedia = $this->Media->getObjectList('Product', $id);
		
		// for bin-file we just upload an image with the same name + _thumb
		$aThumbs = array();
		foreach($aMedia as $media) {
			if ($media['Media']['media_type'] == 'image' && strpos($media['Media']['orig_fname'], '_thumb') !== false) {
				list($fname) = explode('.', str_replace('_thumb', '', $media['Media']['orig_fname']));
				$aThumbs[$fname] = $media;
			}
		}
		$aMedia = Hash::combine($aMedia, '{n}.Media.id', '{n}', '{n}.Media.media_type');
		$this->set('aMedia', $aMedia);
		$this->set('aThumbs', $aThumbs);
		*/
	}
	
}
