<?php
App::uses('AppController', 'Controller');
App::uses('SiteRouter', 'Lib/Routing');
class SitemapController extends AppController {
	public $name = 'Sitemap';
	public $components = array('RequestHandler');
	public $uses = array('Article.Article', 'Product', 'SectionArticle');

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
}
