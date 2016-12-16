<?php
App::uses('AppController', 'Controller');
App::uses('SiteRouter', 'Lib/Routing');
App::uses('Article', 'Article.Model');
App::uses('Product', 'Model');
App::uses('SectionArticle', 'Model');
App::uses('Region', 'Model');
App::uses('Catalog', 'Model');
App::uses('Category', 'Model');
App::uses('Page', 'Model');
class SitemapController extends AppController {
	public $name = 'Sitemap';
	public $components = array('RequestHandler');
	public $uses = array('Article.Article', 'Product', 'SectionArticle', 'Region', 'Catalog', 'Category', 'Page');

	const PER_PAGE = 5000;

	public function index() {
		// $this->RequestHandler->setContent('xml');
		$tpl = 'index';
		$productsTotal = 0;
		if ($cat_id = Configure::read('domain.category_id')) { // category subdomain
			$conditions = array('Product.published' => 1);
			$conditions['Product.cat_id'] = $cat_id;
			$productsTotal = $this->Product->find('count', compact('conditions'));
			$this->set('subdomain', Configure::read('domain.category'));
			$tpl = 'index_category';
		} elseif (($subdomain = Configure::read('domain.subdomain')) && $subdomain <> 'www') { // subdomain
			$tpl = 'index_subdomains';
		} else { // www
			$conditions = array('is_subdomain' => 1);
			$subdomainCategories = $this->Category->find('all', compact('conditions'));
			$conditions = array(
				'Product.published' => 1,
				'NOT' => array('Product.cat_id' => Hash::extract($subdomainCategories, '{n}.Category.id'))
			);
			$productsTotal = $this->Product->find('count', compact('conditions'));
		}
		$this->set('productPages', ceil($productsTotal / self::PER_PAGE));
		$this->render($tpl);
	}

	public function articles() {
		$conditions = array('published' => 1);
		$order = array('cat_id', 'subcat_id', 'sorting');
		$model = $this->request->param('objectType');
		$this->loadModel($model);

		if (($subdomain_id = Configure::read('domain.subdomain_id')) && $subdomain_id > SUBDOMAIN_WWW) { // subdomain
			$conditions['subdomain_id'] = $subdomain_id;
		} else {
			$conditions['subdomain_id'] = array(SUBDOMAIN_ALL, SUBDOMAIN_WWW);
		}

		$aArticles = $this->{$model}->find('all', compact('conditions', 'order'));
		$this->set(compact('aArticles'));
	}

	public function plain() {
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

	public function products($page) {
		//$this->RequestHandler->setContent('xml');
		$this->layout = false;
		$this->paginate = array(
			'conditions' => array('Product.published' => 1),
			'limit' => self::PER_PAGE,
			'page' => $page
		);

		if ($cat_id = Configure::read('domain.category_id')) { // category subdomain
			$this->paginate['conditions']['Product.cat_id'] = $cat_id;
			$this->set('subdomain', Configure::read('domain.category'));
		} else { // www
			$conditions = array('is_subdomain' => 1);
			$subdomainCategories = $this->Category->find('all', compact('conditions'));
			$this->paginate['conditions']['NOT'] = array('Product.cat_id' => Hash::extract($subdomainCategories, '{n}.Category.id'));
		}

		$aArticles = $this->paginate('Product');
		$this->set(compact('aArticles'));
		$this->render();
		if (TEST_ENV) {
			// а ХЗ почему так... на prod упаковывает автоматом при указании response->type
			$xml = $this->response->body();
			$this->response->body(gzencode($xml));
		}
		$this->response->type('gzip');
		$this->response->download('sitemap_'.$page.'.xml.gz');
	}

	public function catalog() {

	}
}
