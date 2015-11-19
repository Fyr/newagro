<?php
App::uses('AppModel', 'Model');
App::uses('Seo', 'Seo.Model');
class SeoArticle extends Seo {
	public $useDbConfig = 'vitacars';
	public $useTable = 'seo';

}
