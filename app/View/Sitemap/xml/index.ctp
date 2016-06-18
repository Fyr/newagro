<? echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<?
	$date = date('c');
?>
<sitemapindex xmlns="http://www.google.com/schemas/sitemap/0.84">
<?
	foreach(array('News', 'RepairArticle', 'Offer', 'Brand', 'Motor', 'Dealer', 'SectionArticle') as $objectType) {
?>
	<sitemap>
		<loc><?= Router::url(array('action' => 'articles', 'objectType' => $objectType, 'ext' => 'xml'), true) ?></loc>
		<lastmod><?=$date?></lastmod>
	</sitemap>
<?
	}
?>
	<sitemap>
		<loc><?=Router::url(array('action' => 'plain', 'ext' => 'xml'), true)?></loc>
		<lastmod><?=$date?></lastmod>
	</sitemap>
<?
	for($i = 1; $i <= $productPages; $i++) {
		// $url = Router::url(array('action' => 'products', $i), true);
		$url = 'http://'.Configure::read('domain.url').'/zapchasti/sitemap_'.$i.'.xml.gz';
?>
	<sitemap>
		<loc><?=$url?></loc>
		<lastmod><?=$date?></lastmod>
	</sitemap>
<?
	}
?>
</sitemapindex>