<?php
App::uses('AppController', 'Controller');
App::uses('SiteRouter', 'Lib/Routing');
class SitemapController extends AppController {
	public $name = 'Sitemap';
	public $components = array('RequestHandler');
	public $uses = array('Article.Article', 'Product');

	const PER_PAGE = 50000;

	public function index() {
		$this->RequestHandler->setContent('xml');
		$conditions = array('Product.published' => 1);
		$productsTotal = $this->Product->find('count', compact('conditions'));
		$this->set('productPages', ceil($productsTotal / self::PER_PAGE));
	}

	public function main() {
	}

	public function pages() {
		$order = array('object_type', 'object_id', 'cat_id', 'subcat_id');
		$aArticles = $this->Article->find('all', compact('order'));
		$this->set(compact('aArticles'));
	}

	public function products($page) {
		$this->paginate = array(
			'conditions' => array('Product.published' => 1),
			'limit' => self::PER_PAGE,
			'page' => $page
		);
		$aArticles = $this->paginate('Product');
		$this->set(compact('aArticles'));
	}
}
