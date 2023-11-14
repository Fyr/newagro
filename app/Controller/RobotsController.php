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
		$tpl = 'robots.txt';
		$this->response->type('text/plain');
		$this->render(self::ROBOTS_PATH.$tpl);
	}
}
