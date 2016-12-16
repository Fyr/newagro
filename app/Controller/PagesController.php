<?php
App::uses('AppController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('Page', 'Model');
App::uses('News', 'Model');
App::uses('SiteArticle', 'Model');
App::uses('Region', 'Model');
App::uses('Category', 'Model');
class PagesController extends AppController {
	public $name = 'Pages';
	public $uses = array('Page', 'News', 'Region', 'Marker', 'Category');
	public $helpers = array('ArticleVars', 'Media.PHMedia', 'Core.PHTime', 'Media');

	public function home() {
		$this->set('isHomePage', true);
		
		$conditions = array('News.published' => 1, 'News.subdomain_id' => array(SUBDOMAIN_ALL, $this->getSubdomainId()));
		if ($this->aEvents) {
			$conditions['NOT'] = array('News.id' => Hash::extract($this->aEvents, '{n}.News.id'));
		}
		$aNews = $this->News->find('all', array(
			'conditions' => $conditions,
			'order' => array('News.subdomain_id DESC', 'News.featured DESC', 'News.sorting ASC', 'News.created DESC'),
			'limit' => 3
		));
		$this->set('aHomePageNews', $aNews);

		if ($cat_id = Configure::read('domain.category_id')) {
			$aArticle = $this->Category->findById($cat_id);
			$aArticle['Page'] = $aArticle['Category'];
		} else {
			$aArticle = $this->Page->getBySlug('home');
		}
		$this->set('contentArticle', $aArticle);
		
		if (!(isset($aArticle['Seo']) & isset($aArticle['Seo']['title']) && $aArticle['Seo']['title'])) {
			$aArticle['Seo']['title'] = $aArticle['Page']['title'];
		}
		$this->seo = $aArticle['Seo'];

		$aRegions = Hash::combine($this->Region->find('all'), '{n}.Region.id', '{n}.Region');
		$aMarkers = $this->Marker->find('all');
		$aMarkers = Hash::combine($aMarkers, '{n}.Marker.id', '{n}.Marker', '{n}.Marker.region_id'); // group by region
		$this->set(compact('aRegions', 'aMarkers'));
	}
	
	public function show($slug) {
		$aArticle = $this->Page->getBySlug($slug);
		if (!$aArticle) {
			$this->redirect404();
			return;
		}
		$this->set('aArticle', $aArticle);
		
		if (!(isset($aArticle['Seo']) & isset($aArticle['Seo']['title']) && $aArticle['Seo']['title'])) {
			$aArticle['Seo']['title'] = $aArticle['Page']['title'];
		}
		$this->seo = $aArticle['Seo'];
		$this->currMenu = $slug;
	}

	public function nonExist() {
		$this->render('/Errors/error400');
	}

	public function region($id) {
		$region = $this->Region->findById($id);
		if (!$region) {
			$this->redirect404();
			return;
		}

		$conditions = array('region_id' => $id);
		$order = 'sorting';
		$aSubdomains = $this->Subdomain->find('all', compact('conditions', 'order'));
		$this->set(compact('region', 'aSubdomains'));
	}
}
