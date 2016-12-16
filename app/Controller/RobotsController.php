<?php
App::uses('Category', 'Model');
App::uses('News', 'Model');
App::uses('Offer', 'Model');
App::uses('Page', 'Model');
App::uses('SiteRouter', 'Lib/Routing');
class RobotsController extends Controller {
	public $name = 'Robots';
	public $layout = false, $autoRender = false;
	public $uses = array('Category', 'News', 'Offer', 'Page');

	const ROBOTS_PATH = '../../webroot/';

	public function index() {
		$conditions = array('slug' => Configure::read('domain.subdomain'), 'is_subdomain' => 1);
		$category = $this->Category->find('first', compact('conditions'));
		if ($category) {
			Configure::write('domain.category', Configure::read('domain.subdomain'));
			Configure::write('domain.subdomain', 'www');
		}

		// Для .UA субдомен всегда www и свой robots.txt, который отдается напрямую
		if (Configure::read('domain.category')) { // category subdomain
			$subdomain = Configure::read('domain.category');

			$this->set(compact('category', 'subdomain'));
			$tpl = 'robots_category.txt';
		} elseif (($subdomain = Configure::read('domain.subdomain')) && $subdomain <> 'www') { // subdomain
			App::uses('Subdomain', 'Model');
			$this->Subdomain = new Subdomain();
			$subdomain_id = Hash::get($this->Subdomain->findByName($subdomain), 'Subdomain.id');

			$conditions = array('published' => 1, 'subdomain_id' => $subdomain_id);

			$news = $this->News->find('all', compact('conditions'));
			$offers = $this->Offer->find('all', compact('conditions'));

			$pages = array();
			if ($page = $this->Page->findBySubdomainIdAndSlug($subdomain_id, 'about-us')) {
				$pages[] = $page;
			}

			$articles = array_merge($news, $offers, $pages);
			$this->set(compact('subdomain', 'articles'));
			$tpl = 'robots_subdomains.txt';
		} else { // www
			$conditions = array('is_subdomain' => 1);
			$subdomainCategories = $this->Category->find('all', compact('conditions'));

			$conditions = array('published' => 1, 'subdomain_id > ' => SUBDOMAIN_WWW);
			$news = $this->News->find('all', compact('conditions'));
			$offers = $this->Offer->find('all', compact('conditions'));
			$articles = array_merge($news, $offers);

			$this->set(compact('subdomainCategories', 'articles'));
			$tpl = 'robots.txt';
		}

		$this->response->type('text/plain');
		$this->render(self::ROBOTS_PATH.$tpl);
	}
}
