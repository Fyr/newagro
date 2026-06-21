<?php
App::uses('AppHelper', 'View/Helper');
class MediaHelper extends AppHelper {
	public $MediaPath;

	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);

		App::uses('MediaPath', 'Media.Vendor');
		$this->MediaPath = new MediaPath();
	}

	function imageUrl($article, $size = 'noresize') {
		if (isset($article['Media']) && isset($article['Media']['id']) && $article['Media']['id']) {
			$media = $article['Media'];
		} elseif (isset($article['MediaArticle']) && isset($article['MediaArticle']['id']) && $article['MediaArticle']['id']) {
			$media = $article['MediaArticle'];
		} else {
			return '';
		}

		return str_replace('noresize', $size, $media['url_img']);
		// return $this->MediaPath->getImageUrl($media['object_type'], $media['id'], $size, $media['file'].$media['ext']);
	}
}
