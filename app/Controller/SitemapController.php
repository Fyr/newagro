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
		$cacheKey = 'www_index.xml';
		if ($cat_id = Configure::read('domain.category_id')) { // category subdomain
			$slug = Configure::read('domain.category');
			$cacheKey = $slug.'_index_category.xml';
		} elseif (($subdomain = Configure::read('domain.subdomain')) && $subdomain <> 'www') { // subdomain
			$cacheKey = $subdomain.'_index_subdomains.xml';
		}
		$cacheFilename = $this->_getCacheFilename($cacheKey);

		if (Configure::read('sitemap.cache') && file_exists($cacheFilename)) {
			$cacheModified = filemtime($cacheFilename);

			// Берем created, а не modified т.к. для sitemap важен URL (т.е. slug), а не просто модификация данных
			// slug вряд ли меняется после создания статьи, в крайнем случае кэш обновится при создании новой статьи
			$fields = array('Article.id', 'Article.created');
			$conditions = array('Article.published' => 1);
			$order = array('Article.created' => 'DESC');
			$article = $this->Article->find('first', compact('fields', 'conditions', 'order'));
			$modified = strtotime($article['Article']['created']);
			// fdebug(array($product['Product']['created'], $productModified, date('Y-m-d H:i:s', $cacheModified), $cacheModified));

			// Из кэша берем данные только тогда когда кэш обновлялся после модифицации продукта
			// (т.е. ничего с момента формирования кэша не изменилось)
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
			// исключаем из sitemap категории-субдомены, т.к. у них свой sitemap
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

	public function articles() {
		$model = $this->request->param('objectType');
		if (($subdomain = Configure::read('domain.subdomain')) && $subdomain <> 'www') { // subdomain
			$cacheKey = $subdomain.'_articles_'.$model.'.xml';
		} else {
			$cacheKey = 'www_articles_'.$model.'.xml';
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
		// формируется быстро - кэш не нужен
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

	private function _getFromCache($cacheKey, $category_ids) {
		$cacheFilename = $this->_getCacheFilename($cacheKey);
		if (Configure::read('sitemap.cache') && file_exists($cacheFilename)) {
			$cacheModified = filemtime($cacheFilename);

			// Берем created, а не modified т.к. для sitemap важен URL (т.е. slug), а не просто модификация данных
			// slug вряд ли меняется после создания продукта, в крайнем случае кэш обновится при создании нового продукта
			$fields = array('Product.id', 'Product.created', 'Product.cat_id');
			$conditions = array('Product.cat_id' => $category_ids);
			$order = array('Product.created' => 'DESC');
			$product = $this->Product->find('first', compact('fields', 'conditions', 'order'));
			$productModified = strtotime($product['Product']['created']);
			// fdebug(array($product['Product']['created'], $productModified, date('Y-m-d H:i:s', $cacheModified), $cacheModified));

			// Из кэша берем данные только тогда когда кэш обновлялся после модифицации продукта
			// (т.е. ничего с момента формирования кэша не изменилось)
			if ($productModified < $cacheModified && $body = $this->_readCache($cacheKey)) {
				return $body;
			}
		}
		return false;
	}

	private function _getProducts($page, $aCategoryOptions) {
		$this->paginate = array(
			'fields' => array('Product.id', 'Product.slug', 'Product.cat_id', 'Product.subcat_id'),
			'conditions' => array('Product.published' => 1, 'Product.cat_id' => array_keys($aCategoryOptions)),
			'limit' => self::PER_PAGE,
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
			$article['Category'] = $aCategoryOptions[$cat_id]['Category'];
			if (isset($subcategories[$subcat_id])) { // подкатегория у продукта может отсутствовать
				$article['Subcategory'] = $subcategories[$subcat_id]['Subcategory'];
			}
		}
		return $aArticles;
	}

	public function products($page) {
		$cacheKey = 'www_all_products_'.$page.'.xml';

		$conditions = array('is_subdomain' => 0);
		$fields = array('id', 'title', 'slug', 'is_subdomain');
		$categories = $this->Category->find('all', compact('fields', 'conditions'));
		$categories = Hash::combine($categories, '{n}.Category.id', '{n}');

		if ($body = $this->_getFromCache($cacheKey, array_keys($categories))) {
			$this->response->body($body);
			$this->autoRender = false;
			return;
		}

		$aArticles = $this->_getProducts($page, $categories);

		$this->set(compact('aArticles'));
		$this->render('all_products');
		$this->_writeCache($cacheKey, $this->response->body());
	}

	public function subdomain_products($page) {
		$this->layout = false;
		$this->response->header(array(
			'Content-Encoding' => 'gzip',
			'Content-Type' => 'text/xml'
		));

		$cat_id = Configure::read('domain.category_id');
		$slug = Configure::read('domain.category');
		$cacheKey = $slug.'_products_'.$page.'.xml.gz';
		$outName = 'sitemap_'.$page.'.xml.gz';

		if ($body = $this->_getFromCache($cacheKey, $cat_id)) {
			$this->response->body($body);
			$this->response->download($outName);
			$this->autoRender = false;
			return;
		}

		$category = $this->Category->findById($cat_id, array('id', 'slug', 'is_subdomain'));
		$aArticles = $this->_getProducts($page, array($cat_id => $category));

		$this->set(compact('aArticles'));
		$this->render('products');

		$this->response->body(gzencode($this->response->body())); // $this->response->type('gzip');
		$this->_writeCache($cacheKey, $this->response->body());
		$this->response->download($outName);
	}

	/* На данный момент все категории для продуктов это субдомены
	public function category_products($slug, $page) {
		$this->layout = false;
		$this->response->header(array(
			'Content-Encoding' => 'gzip',
			'Content-Type' => 'text/xml'
		));

		$category = $this->Category->findBySlug($slug, array('id', 'slug', 'is_subdomain'));
		$cat_id = $category['Category']['id'];
		$cacheKey = 'www_products_'.$slug.'_'.$page.'.xml.gz';
		$outName = 'sitemap_'.$page.'.xml.gz';

		if ($body = $this->_getFromCache($cacheKey, $cat_id)) {
			$this->response->body($body);
			$this->response->download($outName);
			$this->autoRender = false;
			return;
		}

		$aArticles = $this->_getProducts($page, array($cat_id => $category));

		$this->set(compact('aArticles'));
		$this->render('products');

		$this->response->body(gzencode($this->response->body())); // $this->response->type('gzip');
		$this->_writeCache($cacheKey, $this->response->body());
		$this->response->download($outName);
	}
*/
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
