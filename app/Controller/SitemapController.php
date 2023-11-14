<?php
App::uses('AppController', 'Controller');
App::uses('SiteRouter', 'Lib/Routing');
App::uses('Media', 'Media.Model');
App::uses('Article', 'Article.Model');
App::uses('Product', 'Model');
App::uses('SectionArticle', 'Model');
App::uses('Region', 'Model');
App::uses('Catalog', 'Model');
App::uses('Category', 'Model');
App::uses('Subcategory', 'Model');
App::uses('Subdomain', 'Model');
App::uses('Page', 'Model');
class SitemapController extends AppController {
	public $name = 'Sitemap';
	public $components = array('RequestHandler');
	public $uses = array('Article.Article', 'Product', 'SectionArticle', 'Region', 'Catalog', 'Category', 'Subcategory', 'Page', 'Subdomain');
	public $layout = false; // no layout needed for XMLs

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Category->unbindModel(array('hasOne' => array('Seo')), false);
		$this->Product->unbindModel(array(
			'belongsTo' => array('Category', 'Subcategory'),
			'hasOne' => array('Seo', 'MediaArticle', 'Search')
		), false);

	}

	public function beforeFilterLayout() {
	}

	public function beforeRenderLayout() {
	}

	public function index() {
	}

	public function articles() {
		$model = $this->request->param('objectType');
		$cacheKey = 'articles_'.$model.'.xml';
		if ($this->_loadCache($cacheKey)) {
			return;
		}

		$this->loadModel($model);
		$this->_unbindModels($this->{$model});

		$conditions = array('published' => 1);
		$order = array('cat_id', 'subcat_id', 'sorting');
		$aArticles = $this->{$model}->find('all', compact('conditions', 'order'));
		$this->set(compact('aArticles'));
		$this->render();
		$this->_writeCache($cacheKey, $this->response->body());
	}

	public function plain() {
		$cacheKey = 'plain.xml';
		if ($this->_loadCache($cacheKey)) {
			return;
		}

		$aURL = array(
			array('controller' => 'pages', 'action' => 'show', 'about-us'),
			array('controller' => 'contacts', 'action' => 'index')
		);
		
		// get all subdomains except 1st one (www)
		$aSubdomains = $this->Subdomain->find('all');
		array_shift($aSubdomains);
		foreach($aSubdomains as $subdomain) {
			$slug = $subdomain['Subdomain']['name'];
			$aURL[] = array('controller' => 'Pages', 'action' => 'home', 'filial' => $slug);
			$aURL[] = array('controller' => 'Pages', 'action' => 'show', 'about-us', 'filial' => $slug);
			$aURL[] = array('controller' => 'Contacts', 'action' => 'index', 'filial' => $slug);
		}

		$aRegions = $this->Region->getOptions();

		$this->loadModel('Media.Media'); // loadMedia before Catalog !!!
		$aCatalogFiles = $this->Catalog->findAllByPublished(1);
		$this->set(compact('aURL', 'aRegions', 'aCatalogFiles'));
		$this->render();
		$this->_writeCache($cacheKey, $this->response->body());
	}

	public function product_categories() {
		$cacheKey = 'product_Categories.xml';
		if ($this->_loadCache($cacheKey)) {
			return;
		}

		$zone = Configure::read('domain.zone');
		$conditions = array("export_$zone" => 1);
		$fields = array('id', 'title', 'slug');
		$order = array('sorting ASC');
		$categories = $this->Category->find('all', compact('fields', 'conditions', 'order'));

		$fields = array('COUNT(*) AS count', 'Product.cat_id');
		$conditions = array(
			'Product.published' => 1,
			'Product.cat_id' => Hash::extract($categories, '{n}.Category.id'),
			'Product.is_fake' => 0
		);
		$group = array('Product.cat_id');
		$productsTotal = $this->Product->find('all', compact('fields', 'conditions', 'group'));
		$productsTotal = Hash::combine($productsTotal, '{n}.Product.cat_id', '{n}.0.count');
		$aCategories = array();
		foreach($categories as $row) {
			$_cat_id = Hash::get($row, 'Category.id');
			if (isset($productsTotal[$_cat_id]) && $productsTotal[$_cat_id]) {
				$row['Product']['pages'] = ceil($productsTotal[$_cat_id] / Configure::read('sitemap.per_page'));
				$aCategories[] = $row;
			}
		}
		$this->set(compact('aCategories'));
		$this->render();
		$this->_writeCache($cacheKey, $this->response->body());
	}

	private function _getProducts($page, $category) {
		$this->paginate = array(
			'fields' => array('Product.id', 'Product.slug', 'Product.cat_id', 'Product.subcat_id', 'Product.is_fake'),
			'conditions' => array('Product.published' => 1, 'Product.cat_id' => $category['Category']['id'], 'Product.is_fake' => 0),
			'limit' => Configure::read('sitemap.per_page'),
			'page' => $page
		);
		$aArticles = $this->paginate('Product');
		
		// Добавить подкатегории вручную - сокращает время запроса на count(*) Product
		$this->_unbindModels($this->Subcategory);
		$subcat_ids = array_unique(Hash::extract($aArticles, '{n}.Product.subcat_id'));
		$subcategories = $this->Subcategory->findAllById($subcat_ids, array('id', 'slug'));
		$subcategories = Hash::combine($subcategories, '{n}.Subcategory.id', '{n}');

		foreach($aArticles as &$article) {
			$cat_id = $article['Product']['cat_id'];
			$subcat_id = $article['Product']['subcat_id'];
			$article['Category'] = $category['Category'];
			if (isset($subcategories[$subcat_id])) { // подкатегория у продукта может отсутствовать
				$article['Subcategory'] = $subcategories[$subcat_id]['Subcategory'];
			}
		}
		
		return $aArticles;
	}

	public function products($slug, $page) {
		$cacheKey = 'products_'.$slug.'_'.$page.'.xml.gz';
		$outName = 'sitemap_'.$page.'.xml.gz';
		if ($this->_loadCache($cacheKey)) {
			$this->response->download($outName);
			return;
		}

		$this->response->header(array(
			'Content-Encoding' => 'gzip',
			'Content-Type' => 'text/xml'
		));
		$category = $this->Category->findBySlug($slug, array('id', 'slug'));
		$cat_id = $category['Category']['id'];
		$aArticles = $this->_getProducts($page, $category);
		$this->set(compact('aArticles'));
		$this->render();
		$this->response->body(gzencode($this->response->body())); // $this->response->type('gzip');
		$this->_writeCache($cacheKey, $this->response->body());
		$this->response->download($outName);
	}

	/**
	 * Отключаем ненужные модели для hasOne
	 */
	private function _unbindModels(Model $Model) {
		$aUnbind = array();
		foreach(array('Seo', 'Media', 'MediaArticle') as $subModel) {
			if (isset($Model->hasOne[$subModel])) {
				$aUnbind[] = $subModel;
			}
		}
		$Model->unbindModel(array('hasOne' => $aUnbind));
	}

	private function _writeCache($key, $body) {
		file_put_contents($this->_getCacheFilename($key), $body);
	}

	private function _readCache($key) {
		$fname = $this->_getCacheFilename($key);
		if (file_exists($fname)) {
			return file_get_contents($fname);
		}
		return '';
	}

	private function _loadCache($key) {
		if ($body = $this->_readCache($key)) {
			$this->response->body($body);
			$this->autoRender = false;
		}
		return $body;
	}

	public function catalog() {
		$this->redirect(array('controller' => 'pages', 'action' => 'nonExist', 'ext' => null));
	}
}
