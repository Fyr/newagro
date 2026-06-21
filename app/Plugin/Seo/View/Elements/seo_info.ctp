<?
/**
 * Renders SEO info on page
 * @param $data
 */

    $zone = Configure::read('domain.zone') == 'by' ? 'в Беларуси' : 'в России';

	$title = (isset($data['title']) && $data['title'])
	    ? $data['title']
	    : 'Купить запчасти Deutz в '.$zone.' с доставкой';

    $descr = (isset($data['descr']) && $data['descr'])
        ? $data['descr']
        : 'Запчасти Deutz '.$zone.' | Оригинальное качество из Германии | Низкие цены запчасти Дойц в наличии | + 7 495 241 00 96';

    $keywords = (isset($data['keywords']) && $data['keywords'])
        ? $data['keywords']
        : '';

    echo $this->Html->tag('title', $this->PHSeo->addPagingTitle($title))."\n";
    echo $this->Html->meta('description', $this->PHSeo->addPagingTitle($title))."\n";
    echo $this->Html->meta('keywords', $keywords)."\n";

	$image = (isset($data['image']) && $data['image'])
	    ? $data['image']
	    : '/img/logo2_footer.png';

	$ogTags = array(
		'og:title' => $title,
		'og:type' => 'website',
		'og:url' => HTTP.Configure::read('domain.url').$_SERVER['REQUEST_URI'],
		'og:image' => HTTP.Configure::read('domain.url').$image,
		'og:description' => $descr
	);

	foreach($ogTags as $property => $content) {
		echo $this->Html->tag('meta', null, compact('property', 'content'))."\n";
	}

	echo $this->Html->tag('link', null, array('rel' => 'image_src', 'href' => $ogTags['og:image']))."\n";
