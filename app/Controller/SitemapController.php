<?php
App::uses('AppController', 'Controller');
App::uses('SiteRouter', 'Lib/Routing');
App::uses('Article', 'Article.Model');
App::uses('Product', 'Model');
App::uses('SectionArticle', 'Model');
App::uses('Region', 'Model');
App::uses('Catalog', 'Model');
App::uses('Category', 'Model');
App::uses('Subcategory', 'Model');
App::uses('Page', 'Model');
class SitemapController extends AppController {
	public $name = 'Sitemap';
	public $components = array('RequestHandler');
	public $uses = array('Article.Article', 'Product', 'SectionArticle', 'Region', 'Catalog', 'Category', 'Subcategory', 'Page');

	const PER_PAGE = 5000;

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
		// $this->RequestHandler->setContent('xml');
		$cacheKey = 'index.xml';
		if ($cat_id = Configure::read('domain.category_id')) { // category subdomain
			$cacheKey = 'index_category.xml';
		} elseif (($subdomain = Configure::read('domain.subdomain')) && $subdomain <> 'www') { // subdomain
			$cacheKey = 'index_subdomains.xml';
		}
		$cacheFilename = $this->_getCacheFilename($cacheKey);

		if (Configure::read('sitemap.cache') && file_exists($cacheFilename)) {
			$cacheModified = filemtime($cacheFilename);

			// ����� created, � �� modified �.�. ��� sitemap ����� URL (�.�. slug), � �� ������ ����������� ������
			// slug ���� �� �������� ����� �������� ������, � ������� ������ ��� ��������� ��� �������� ����� ������
			$fields = array('Article.id', 'Article.created');
			$conditions = array('Article.published' => 1);
			$order = array('Article.created' => 'DESC');
			$article = $this->Article->find('first', compact('fields', 'conditions', 'order'));
			$modified = strtotime($article['Article']['created']);
			// fdebug(array($product['Product']['created'], $productModified, date('Y-m-d H:i:s', $cacheModified), $cacheModified));

			// �� ���� ����� ������ ������ ����� ����� ��� ���������� ����� ����������� ��������
			// (�.�. ������ � ������� ������������ ���� �� ����������)
			if ($modified < $cacheModified && $body = $this->_readCache($cacheKey)) {
				$this->response->body($body);
				$this->autoRender = false;
				return;
			}
		}

		$tpl = 'index';
		if ($cat_id = Configure::read('domain.category_id')) { // category subdomain
			$conditions = array('Product.published' => 1);
			$conditions['Product.cat_id'] = $cat_id;
			$productsTotal = $this->Product->find('count', compact('conditions'));
			$this->set('productPages', ceil($productsTotal / self::PER_PAGE));
			$this->set('subdomain', Configure::read('domain.category'));
			$this->set('category', $this->Category->findById($cat_id));
			$tpl = 'index_category';
		} elseif (($subdomain = Configure::read('domain.subdomain')) && $subdomain <> 'www') { // subdomain
			$tpl = 'index_subdomains';
		} else { // www
			// ��������� �� sitemap ���������-���������, �.�. � ��� ���� sitemap
			$conditions = array('is_subdomain' => 0);
			$fields = array('id', 'title', 'slug', 'is_subdomain');
			$categories = $this->Category->find('all', compact('fields', 'conditions'));

			$fields = array('COUNT(*) AS count', 'Product.cat_id');
			$conditions = array(
				'Product.published' => 1,
				'Product.cat_id' => Hash::extract($categories, '{n}.Category.id')
			);
			$group = array('Product.cat_id');
			$productsTotal = $this->Product->find('all', compact('fields', 'conditions', 'group'));
			$productsTotal = Hash::combine($productsTotal, '{n}.Product.cat_id', '{n}.0.count');
			$aCategories = array();
			foreach($categories as $row) {
				$_cat_id = Hash::get($row, 'Category.id');
				if (isset($productsTotal[$_cat_id]) && $productsTotal[$_cat_id]) {
					$row['Product']['pages'] = ceil($productsTotal[$_cat_id] / self::PER_PAGE);
					$aCategories[] = $row;
				}
			}
			$this->set('aCategories', $aCategories);
		}

		// $this->response->type('text/html');
		$this->layout = false;
		$this->render($tpl);
		$this->_writeCache($cacheKey, $this->response->body());
	}

	/**
	 * ��������� �������� ������ ��� hasOne
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

	public function articles() {
		$model = $this->request->param('objectType');
		if (($subdomain = Configure::read('domain.subdomain')) && $subdomain <> 'www') { // subdomain
			$cacheKey = 'articles_'.$model.'_'.$subdomain.'.xml';
		} else {
			$cacheKey = 'articles_'.$model.'.xml';
		}

		if (Configure::read('sitemap.cache') && $body = $this->_readCache($cacheKey)) {
			$this->response->body($body);
			$this->autoRender = false;
			return;
		}

		$conditions = array('published' => 1);
		$order = array('cat_id', 'subcat_id', 'sorting');
		$this->loadModel($model);
		$this->_unbindModels($this->{$model});

		if (in_array($model, array('Page', 'News', 'Offer'))) {
			if (($subdomain_id = Configure::read('domain.subdomain_id')) && $subdomain_id > SUBDOMAIN_WWW) { // subdomain
				$conditions['subdomain_id'] = $subdomain_id;
			} else {
				$conditions['subdomain_id'] = array(SUBDOMAIN_ALL, SUBDOMAIN_WWW);
			}
		}

		$aArticles = $this->{$model}->find('all', compact('conditions', 'order'));
		$this->set(compact('aArticles'));
		$this->render();
		$this->_writeCache($cacheKey, $this->response->body());
	}

	public function plain() {
		// ����������� ������ - ��� �� �����
		$tpl = 'plain';
		if (($subdomain_id = Configure::read('domain.subdomain_id')) && $subdomain_id > SUBDOMAIN_WWW) { // subdomain
			$aURL = array(
				array('controller' => 'contacts', 'action' => 'index')
			);
			if ($page = $this->Page->findBySubdomainIdAndSlug($subdomain_id, 'about-us')) {
				$aURL[] = array('controller' => 'pages', 'action' => 'show', 'about-us');
			}
			$this->set(compact('aURL'));
			$tpl = 'plain_subdomains';
		} else {
			$aRegions = $this->Region->getOptions();

			$aCatalog = $this->Catalog->findAllByPublished(1);
			$aCatalogFiles = Hash::extract($aCatalog, '{n}.Media.{n}.url_download');

			$this->set(compact('aRegions', 'aCatalogFiles'));
		}
		$this->render($tpl);
	}

	private function _isCacheReadable($cacheKey) {
		$cacheFilename = $this->_getCacheFilename($cacheKey);
		if (!Configure::read('sitemap.cache') || !file_exists($cacheFilename)) {
			return false;
		}
		$cacheModified = filectime($this->_getCacheFilename($cacheKey));

		return true;
	}

	public function zapchasti($page) {
		$cacheKey = 'zapchasti_'.$page.'.xml';
		$cacheFilename = $this->_getCacheFilename($cacheKey);

		$conditions = array('is_subdomain' => 0);
		$fields = array('id', 'title', 'slug', 'is_subdomain');
		$categories = $this->Category->find('all', compact('fields', 'conditions'));
		$categories = Hash::combine($categories, '{n}.Category.id', '{n}');

		if (Configure::read('sitemap.cache') && file_exists($cacheFilename)) {
			$cacheModified = filemtime($cacheFilename);

			// ����� created, � �� modified �.�. ��� sitemap ����� URL (�.�. slug), � �� ������ ����������� ������
			// slug ���� �� �������� ����� �������� ��������, � ������� ������ ��� ��������� ��� �������� ������ ��������
			$fields = array('Product.id', 'Product.created', 'Product.cat_id');
			$conditions = array('Product.cat_id' => array_keys($categories));
			$order = array('Product.created' => 'DESC');
			$product = $this->Product->find('first', compact('fields', 'conditions', 'order'));
			$productModified = strtotime($product['Product']['created']);
			// fdebug(array($product['Product']['created'], $productModified, date('Y-m-d H:i:s', $cacheModified), $cacheModified));

			// �� ���� ����� ������ ������ ����� ����� ��� ���������� ����� ����������� ��������
			// (�.�. ������ � ������� ������������ ���� �� ����������)
			if ($productModified < $cacheModified && $body = $this->_readCache($cacheKey)) {
				$this->response->body($body);
				$this->autoRender = false;
				return;
			}
		}

		$this->paginate = array(
			'conditions' => array('Product.published' => 1),
			'limit' => self::PER_PAGE,
			'page' => $page
		);

		$this->paginate['conditions']['Product.cat_id'] = array_keys($categories);
		$aArticles = $this->paginate('Product');

		// �������� ������������ ������� - ��������� ����� ������� �� count(*) Product
		$this->_unbindModels($this->Subcategory);
		$subcategories = $this->Subcategory->findAllById(array_unique(Hash::extract($aArticles, '{n}.Product.subcat_id')));
		$subcategories = Hash::combine($subcategories, '{n}.Subcategory.id', '{n}');
		foreach($aArticles as &$article) {
			$cat_id = $article['Product']['cat_id'];
			$subcat_id = $article['Product']['subcat_id'];
			$article['Category'] = $categories[$cat_id]['Category'];
			if (isset($subcategories[$subcat_id])) {
				$article['Subcategory'] = $subcategories[$subcat_id]['Subcategory'];
			}
		}

		$this->set(compact('aArticles'));
		$this->render('zapchasti');

		$this->_writeCache($cacheKey, $this->response->body());
	}


	public function products($slug, $page = 0) {
		if (is_numeric($slug) && !$page) { // ������ �� ���� �� ��������
			$page = $slug;
			return $this->zapchasti($page);
		}
		$this->response->header(array(
			'Content-Encoding' => 'gzip',
			'Content-Type' => 'text/xml'
		));

		$cat_id = Configure::read('domain.category_id');
		$cacheKey = 'products_'.$slug.'_'.$page.'_'.$cat_id.'.xml.gz';
		$cacheFilename = $this->_getCacheFilename($cacheKey);

		if ($cat_id) {
			$category = $this->Category->findById($cat_id);
		} else {
			$category = $this->Category->findBySlug($slug);
			$cat_id = $category['Category']['id'];
		}

		if (Configure::read('sitemap.cache') && file_exists($cacheFilename)) {
			$cacheModified = filemtime($cacheFilename);

			// ����� created, � �� modified �.�. ��� sitemap ����� URL (�.�. slug), � �� ������ ����������� ������
			// slug ���� �� �������� ����� �������� ��������, � ������� ������ ��� ��������� ��� �������� ������ ��������
			$fields = array('Product.id', 'Product.created', 'Product.cat_id');
			$conditions = array('Product.cat_id' => $cat_id);
			$order = array('Product.created' => 'DESC');
			$product = $this->Product->find('first', compact('fields', 'conditions', 'order'));
			$productModified = strtotime($product['Product']['created']);
			// fdebug(array($product['Product']['created'], $productModified, date('Y-m-d H:i:s', $cacheModified), $cacheModified));

			// �� ���� ����� ������ ������ ����� ����� ��� ���������� ����� ����������� ��������
			// (�.�. ������ � ������� ������������ ���� �� ����������)
			if ($productModified < $cacheModified && $body = $this->_readCache($cacheKey)) {
				$this->response->body($body);
				$this->response->download('sitemap_'.$page.'.xml.gz');
				$this->autoRender = false;
				return;
			}
		}

		$this->layout = false;
		$this->paginate = array(
			'conditions' => array('Product.published' => 1),
			'limit' => self::PER_PAGE,
			'page' => $page
		);

		$this->paginate['conditions']['Product.cat_id'] = $cat_id;
		$aArticles = $this->paginate('Product');

		// �������� ������������ ������� - ��������� ����� ������� �� count(*) Product
		$this->_unbindModels($this->Subcategory);
		$subcategories = $this->Subcategory->findAllById(array_unique(Hash::extract($aArticles, '{n}.Product.subcat_id')));
		$subcategories = Hash::combine($subcategories, '{n}.Subcategory.id', '{n}');
		foreach($aArticles as &$article) {
			$subcat_id = $article['Product']['subcat_id'];
			$article['Category'] = $category['Category'];
			if (isset($subcategories[$subcat_id])) {
				$article['Subcategory'] = $subcategories[$subcat_id]['Subcategory'];
			}
		}

		$this->set(compact('aArticles'));
		$this->render();

		$this->response->body(gzencode($this->response->body()));
		// $this->response->type('gzip');
		$this->_writeCache($cacheKey, $this->response->body());
		$this->response->download('sitemap_'.$page.'.xml.gz');
	}

	private function _getCacheFilename($key) {
		return Configure::read('sitemap.dir').Configure::read('sitemap.prefix').$key;
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

	public function catalog() {
		$this->redirect(array('controller' => 'pages', 'action' => 'nonExist', 'ext' => null));
	}
}
