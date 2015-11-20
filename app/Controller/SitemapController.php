<?php
App::uses('AppController', 'Controller');
class SitemapController extends AppController {
	public $name = 'Sitemap';
	public $components = array('RequestHandler');
	
	public function index() {
		$this->RequestHandler->setContent('xml');
	}
	
}
