<?php
App::uses('AppController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('Page', 'Model');
App::uses('News', 'Model');
App::uses('SiteArticle', 'Model');

class PagesController extends AppController {
	public $name = 'Pages';
	public $uses = array('Page', 'SiteArticle', 'Product', 'News');
	public $helpers = array('ArticleVars', 'Media.PHMedia', 'Core.PHTime', 'Media');

	public function home() {
		/*
		$conditions = array('Article.object_type' => 'news', 'Article.published' => 1);
		if ($this->aEvents) {
			$conditions['Article.id <> '] = $this->aEvents[0]['Article']['id'];
		}
		$aNews = $this->SiteNews->find('all', array(
			'conditions' => $conditions,
			'order' => array('Article.featured DESC', 'Article.created DESC'),
			'limit' => 3
		));
		$this->set('aHomePageNews', $aNews);

		$aID = array();
		foreach($this->aFeaturedProducts as $article) {
			$aID[] = $article['Article']['id'];
		}
		$aFeaturedProducts = $this->Article->find('all', array(
			'conditions' => array('Article.object_type' => 'products', 'Article.featured' => 1, 'Article.published' => 1, 'NOT' => array('Article.id' => $aID)),
			'order' => 'Article.created DESC',
			'limit' => 3
		));

		$this->set('aFeaturedProducts2', $aFeaturedProducts);

		$aID = array();
		foreach($aFeaturedProducts as $row) {
			$aID[] = $row['Article']['id'];
		}
		$aLastProducts = $this->Article->find('all', array(
			'conditions' => array('Article.object_type' => 'products', 'Article.published' => 1, 'NOT' => array('Article.id' => $aID)),
			'order' => 'Article.created DESC',
			'limit' => 8
		));
		$this->set('aLastProducts', $aLastProducts);

		$aArticle = $this->SitePage->findByPageId('home');
		$this->set('contentArticle', $aArticle);

		$this->pageTitle = (isset($aArticle['Seo']['title']) && $aArticle['Seo']['title']) ? $aArticle['Seo']['title'] : $aArticle['Article']['title'];
		$this->data['SEO'] = $aArticle['Seo'];
		*/
		$aArticle = $this->Page->findBySlug('home');
		$this->set('contentArticle', $aArticle);
	}
	
	public function view($slug) {
		$this->request->params['objectType'] = 'Page';
		
		$article = $this->Page->findBySlug($slug);
		$this->set('article', $article);
		
		$this->currMenu = $slug;
	}
}
