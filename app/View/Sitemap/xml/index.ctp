<? echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<sitemapindex xmlns="http://www.google.com/schemas/sitemap/0.84">
<?
	$date = date('c');

	$aObjectTypes = array('News', 'RepairArticle', 'Offer', 'Brand', 'Motor', 'Dealer', 'SectionArticle', 'MachineTool');
	foreach($aObjectTypes as $objectType) {
		$url = Router::url(array('action' => 'articles', 'objectType' => $objectType, 'ext' => 'xml'), true);
		echo $this->element('sitemap_map', compact('url', 'date'));
	}

	$url = Router::url(array('action' => 'plain', 'ext' => 'xml'), true);
	echo $this->element('sitemap_map', compact('url', 'date'));

	/*
	foreach($aCategories as $category) {
		for($i = 1; $i <= $category['Product']['pages']; $i++) {
			$url = SiteRouter::url($category).'/sitemap_'.$i.'.xml.gz';
			echo $this->element('sitemap_map', compact('url', 'date'));
		}
	}
	*/
	$url = Router::url(array('action' => 'product_categories', 'ext' => 'xml'), true);
	echo $this->element('sitemap_map', compact('url', 'date'));

?>
</sitemapindex>
<?//$this->element('sql_dump')?>
<?//$this->element('sql_stats')?>
