<? echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<?
	$date = date('c');
?>
<sitemapindex xmlns="http://www.google.com/schemas/sitemap/0.84">
	<sitemap>
		<loc><?=Router::url(array('action' => 'main', 'ext' => 'xml'), true)?></loc>
		<lastmod><?=$date?></lastmod>
	</sitemap>
	<sitemap>
		<loc><?=Router::url(array('action' => 'pages', 'ext' => 'xml'), true)?></loc>
		<lastmod><?=$date?></lastmod>
	</sitemap>
<?
	for($i = 1; $i <= $productPages; $i++) {
?>
	<sitemap>
		<loc><?=Router::url(array('action' => 'products', 'ext' => 'xml', $i), true)?></loc>
		<lastmod><?=$date?></lastmod>
	</sitemap>
<?
	}
?>
</sitemapindex>