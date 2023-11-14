<? echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?
    $date = date('c');
	foreach($aCategories as $category) {
		for($i = 1; $i <= $category['Product']['pages']; $i++) {
			$url = SiteRouter::url($category).'/sitemap_'.$i.'.xml.gz';
			echo $this->element('sitemap_map', compact('url', 'date'));
		}
	}
?>

</urlset>
