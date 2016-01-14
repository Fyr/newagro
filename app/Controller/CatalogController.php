<?php
App::uses('AppController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('Catalog', 'Model');

class CatalogController extends AppController {
	public $name = 'Catalog';
	public $uses = array('Catalog', 'Media.Media');
	public $helpers = array('Media');

	const PER_PAGE = 100;
	
	function index() {
		$this->paginate = array(
			'conditions' => array('published' => 1),
			'order' => array('Catalog.sorting' => 'asc'),
			'limit' => self::PER_PAGE
		);

		$aArticles = $this->paginate('Catalog');
		$this->set('aArticles', $aArticles);
	}
	
	function download($id) {
		$this->autoRender = false;
		$conditions = array('Media.media_type' => 'raw_file', 'Media.object_type' => 'Catalog', 'Media.object_id' => $id);
		$row = $this->Media->find('first', compact('conditions'));
		$file = '';
		if ($row) {
			App::uses('MediaPath', 'Media.Vendor');
			$this->MediaPath = new MediaPath();
			
			$media = $row['Media'];
			$file = $this->MediaPath->getFileName($media['object_type'], $media['id'], null, $media['file'].$media['ext']);
		}
		
		if ($file && file_exists($file)) {
			header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename='.basename($file));
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    // header('Content-Length: ' . filesize($file));
		    readfile($file);
			/*
			TODO: http://book.cakephp.org/2.0/en/controllers/request-response.html#cakeresponse
			*/
		} else {
			return $this->redirect404();
		}
	}
}
