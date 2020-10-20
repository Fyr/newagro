<? echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<?
	$date = date('c');
?>
<sitemapindex xmlns="http://www.google.com/schemas/sitemap/0.84">
<?
	foreach(array('News', 'Offer') as $objectType) {
		$url = Router::url(array('action' => 'articles', 'objectType' => $objectType, 'ext' => 'xml'), true);
		echo $this->element('sitemap_map', compact('url', 'date'));
	}

	$url = Router::url(array('action' => 'plain', 'ext' => 'xml'), true);
	echo $this->element('sitemap_map', compact('url', 'date'));
?>
</sitemapindex>