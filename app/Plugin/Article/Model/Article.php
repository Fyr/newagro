<?php
App::uses('AppModel', 'Model');
class Article extends AppModel {
	public $useTable = 'pages';

	public $validate = array(
		'title' => 'notempty'
	);
}
