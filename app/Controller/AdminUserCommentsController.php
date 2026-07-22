<?php
App::uses('AdminController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('UserComment', 'Model');
App::uses('SiteArticle', 'Model');

class AdminUserCommentsController extends AdminController {
    public $name = 'AdminUserComments';
    public $components = array('Article.PCArticle');
    public $uses = array('UserComment', 'SiteArticle');
    public $helpers = array('ObjectType');

    public $objectType = 'UserComment';

	public function beforeFilter() {
		parent::beforeFilter();

		$this->PCArticle->setModel($this->objectType);
		$this->set('objectType', $this->objectType);
	}

    public function index($objectID = '') {
        $this->paginate = array(
            'fields' => array('created', 'sorting', 'author', 'body', 'published'),
            'conditions' => array('site_article_id' => $objectID),
            'order' => array('UserComment.sorting' => 'asc')
        );

        $aRowset = $this->PCArticle->index();
        $this->set('objectID', $objectID);
        $this->set('siteArticle', $this->SiteArticle->findById($objectID));
        $this->set('aRowset', $aRowset);
    }

	public function edit($id = 0, $objectID = '') {
	    if ($objectID) {
            $this->request->data('UserComment.site_article_id', $objectID);
	    } else {
	        $objectID = Hash::get($this->UserComment->findById($id), 'UserComment.site_article_id');
	    }
		$this->PCArticle->edit($id, $lSaved);

		if ($lSaved) {
			$indexRoute = array('action' => 'index', $objectID);
			$editRoute = array('action' => 'edit', $id);
			return $this->redirect(($this->request->data('apply')) ? $indexRoute : $editRoute);
		}

		if (!$id) {
			$this->request->data('UserComment.sorting', '0');
		}
	}
}

