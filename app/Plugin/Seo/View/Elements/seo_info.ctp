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

	$ogTags = array(
		'og:title' => $title,
		'og:type' => 'website',
		'og:url' => HTTP.Configure::read('domain.url').'/',
		'og:image' => HTTP.Configure::read('domain.url').'/img/logo2_footer.png',
		'og:description' => (isset($data['descr']) && $data['descr']) ? $data['descr'] : ''
	);

	foreach($ogTags as $property => $content) {
		echo $this->Html->tag('meta', null, compact('property', 'content'))."\n";
	}
	
	echo $this->Html->tag('link', null, array('rel' => 'image_src', 'href' => $ogTags['og:image']))."\n";