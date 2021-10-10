<?
/**
 * Renders SEO info on page
 * @param $data
 */

	$title = (isset($data['title']) && $data['title']) ? $data['title'] : Configure::read('domain.title');
	echo $this->Html->tag('title', $this->PHSeo->addPagingTitle($title))."\n";

	if (isset($data['descr']) && $data['descr']) {
		echo $this->Html->meta('description', $this->PHSeo->addPagingTitle($data['descr']))."\n";
	}

	if (isset($data['keywords']) && $data['keywords']) {
		echo $this->Html->meta('keywords', $data['keywords'])."\n";
	}
