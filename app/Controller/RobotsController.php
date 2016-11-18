<?php
class RobotsController extends Controller {
	public $name = 'Robots';
	public $layout = false, $autoRender = false;

	public function index() {
		// Для .UA свой robots.txt, который отдается напрямую
		if (Configure::read('domain.subdomain') === 'www') {
			$robots = file_get_contents('_robots.txt');
		} else {
			$robots = str_replace('{$subdomain}', Configure::read('domain.subdomain'), file_get_contents('_robots_subdomains.txt'));
		}

		$this->response->type('text/plain');
		$this->response->body($robots);
	}
}
