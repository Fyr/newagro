<?php
App::uses('AppController', 'Controller');
App::uses('SiteRouter', 'Lib/Routing');
App::uses('Article', 'Article.Model');
App::uses('Product', 'Model');
App::uses('SectionArticle', 'Model');
App::uses('Region', 'Model');
App::uses('Catalog', 'Model');
class SitemapController extends AppController {
	public $name = 'Sitemap';
	public $components = array('RequestHandler');
	public $uses = array('Article.Article', 'Product', 'SectionArticle', 'Region', 'Catalog');

	const PER_PAGE = 5000;

	public function index() {
		// $this->RequestHandler->setContent('xml');
		$conditions = array('Product.published' => 1);
		$productsTotal = $this->Product->find('count', compact('conditions'));
		$this->set('productPages', ceil($productsTotal / self::PER_PAGE));
	}

	public function articles() {
		$conditions = array('published' => 1);
		$order = array('cat_id', 'subcat_id', 'sorting');
		$model = $this->request->param('objectType');
		$this->loadModel($model);
		$aArticles = $this->{$model}->find('all', compact('conditions', 'order'));
		$this->set(compact('aArticles'));
	}

	public function plain() {
		$aRegions = $this->Region->getOptions();

		$aCatalog = $this->Catalog->findAllByPublished(1);
		$aCatalogFiles = Hash::extract($aCatalog, '{n}.Media.{n}.url_download');

		$this->set(compact('aRegions', 'aCatalogFiles'));
	}

	public function products($page) {
		//$this->RequestHandler->setContent('xml');
		$this->layout = false;
		$this->paginate = array(
			'conditions' => array('Product.published' => 1),
			'limit' => self::PER_PAGE,
			'page' => $page
		);
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
