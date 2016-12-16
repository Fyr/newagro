<? echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<?
	$date = date('c');
?>
<sitemapindex xmlns="http://www.google.com/schemas/sitemap/0.84">
<?
	foreach(array('News', 'Offer') as $objectType) {
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
</sitemapindex>