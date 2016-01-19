<?php
App::uses('AdminController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('Article', 'Article.Model');
App::uses('Media', 'Media.Model');
App::uses('RepairArticle', 'Model');

class AdminRepairController extends AdminController {
    public $name = 'AdminRepair';
    public $components = array('Article.PCArticle');
    public $uses = array('Article.Article', 'RepairArticle');
    public $helpers = array('ObjectType');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->currMenu = 'RepairArticle';
		$this->set('aCategoryOptions', $this->RepairArticle->getOptions());
	}
    
    public function index($cat_id = 0, $subcat_id = 0) {
		$objectType = 'RepairArticle';
        $this->paginate = array(
				'conditions' => array('RepairArticle.cat_id' => $cat_id),
				'fields' => array('created', 'title', 'slug', 'published', 'sorting'),
				'order' => 'RepairArticle.sorting'
        );
        
        $aRowset = $this->PCArticle->setModel($objectType)->index();
        $this->set('objectType', $objectType);
		$this->set('cat_id', $cat_id);
		$this->set('subcat_id', $subcat_id);
        $this->set('aRowset', $aRowset);
    }

	public function edit($id = 0, $cat_id = 0, $subcat_id = 0) {
		$objectType = 'RepairArticle';
		$this->loadModel('Media.Media');
		
		if (!$id) {
			// если не задан ID, то objectType+ObjectID должны передаваться
			$this->request->data('Article.object_type', $objectType);
			$this->request->data('Article.cat_id', $cat_id);
            // $this->request->data('Article.subcat_id', $subcat_id);
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

		if ($lSaved) {
			$cat_id = $this->request->data('Article.cat_id');
            // $subcat_id = $this->request->data('Article.subcat_id');
			$indexRoute = array('action' => 'index', $cat_id);
			$editRoute = array('action' => 'edit', $id);
			return $this->redirect(($this->request->data('apply')) ? $indexRoute : $editRoute);
		}

		if (!$id && !$this->request->is(array('put', 'post'))) {
			$this->request->data('Article.sorting', '0');
			$this->request->data('Article.status', 'published');
		}
	}
}

