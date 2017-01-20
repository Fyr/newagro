<? echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<sitemapindex xmlns="http://www.google.com/schemas/sitemap/0.84">
<?
	$date = date('c');
	for($i = 1; $i <= $productPages; $i++) {
		// $url = 'http://'.Configure::read('domain.url').'/zapchasti/sitemap_'.$i.'.xml.gz';
		$url = 'http://'.$subdomain.'.'.Configure::read('domain.url').'/zapchasti/sitemap_'.$i.'.xml.gz';
		echo $this->element('sitemap_map', compact('url', 'date'));
	}
?>
</sitemapindex>