<?php
App::uses('AppModel', 'Model');
App::uses('Media', 'Media.Model');
class MediaArticle extends Media {
	public $useDbConfig = 'vitacars';
	public $useTable = 'media';

}
