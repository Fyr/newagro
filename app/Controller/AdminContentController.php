<?php
App::uses('AdminController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('Article', 'Article.Model');
App::uses('Media', 'Media.Model');
App::uses('Page', 'Model');
App::uses('News', 'Model');
App::uses('Offer', 'Model');
App::uses('Motor', 'Model');
App::uses('SiteArticle', 'Model');

class AdminContentController extends AdminController {
    public $name = 'AdminContent';
    public $components = array('Article.PCArticle');
    public $uses = array('Article.Article', 'Page', 'News', 'Offer', 'Motor', 'SiteArticle');
    public $helpers = array('ObjectType');
    
    public function index($objectType, $objectID = '') {
        $this->paginate = array(
            'Page' => array(
            	'fields' => array('title', 'slug')
            ),
        	'News' => array(
        		'fields' => array('created', 'title', 'slug', 'featured', 'published', 'sorting')
        	),
        	'Offer' => array(
        		'fields' => array('created', 'title', 'slug', 'featured', 'published', 'sorting')
        	),
        	'Motor' => array(
        		'fields' => array('created', 'title', 'slug', 'featured', 'published', 'sorting')
        	),
        	'SiteArticle' => array(
        		'fields' => array('created', 'title', 'slug', 'featured', 'published')
        	)
        );
        
        $aRowset = $this->PCArticle->setModel($objectType)->index();
        $this->set('objectType', $objectType);
        $this->set('objectID', $objectID);
        $this->set('aRowset', $aRowset);
        
    }
    
	public function edit($id = 0, $objectType = '', $objectID = '') {
		$this->loadModel('Media.Media');
		
		if (!$id) {
			// если не задан ID, то objectType+ObjectID должны передаваться
			$this->request->data('Article.object_type', $objectType);
			$this->request->data('Seo.object_type', 'Page');
		}
		
		// Здесь работаем с моделью Article, т.к. если задавать только $id, 
		// непонятно какую модель загружать, чтобы определить $objectType
		$this->Article->bindModel(array(
			'hasOne' => array(
				'Seo' => array(
					'className' => 'Seo.Seo',
					'foreignKey' => 'object_id',
					'conditions' => array('Seo.object_type' => 'Page'), // 
					'dependent' => true
				)
			)
		), false);
		
		$this->PCArticle->edit(&$id, &$lSaved);
		$objectType = $this->request->data('Article.object_type');
		
		if ($lSaved) {
			$indexRoute = array('action' => 'index', $objectType, $objectID);
			$editRoute = array('action' => 'edit', $id, $objectType, $objectID);
			return $this->redirect(($this->request->data('apply')) ? $indexRoute : $editRoute);
		}

		if ($objectType == 'SectionArticle') {
			$this->set('aCategoryOptions', $this->Section->getOptions());
			$this->set('aSubcategoryOptions', $this->SectionArticle->find('all', array('order' => 'SectionArticle.sorting')));
		}

		if (!$this->request->data('Article.sorting')) {
			$this->request->data('Article.sorting', '0');
		}
	}
}

