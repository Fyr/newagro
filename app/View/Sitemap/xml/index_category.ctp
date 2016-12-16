<? echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<?
	$date = date('c');
?>
<sitemapindex xmlns="http://www.google.com/schemas/sitemap/0.84">
<?
	for($i = 1; $i <= $productPages; $i++) {
		$url = 'http://'.$subdomain.'.'.Configure::read('domain.url').'/zapchasti/sitemap_'.$i.'.xml.gz';
?>
	<sitemap>
		<loc><?=$url?></loc>
		<lastmod><?=$date?></lastmod>
	</sitemap>
<?
	}
?>
</sitemapindex>