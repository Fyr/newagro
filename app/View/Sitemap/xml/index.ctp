<? 
	echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?
	foreach($aNavBar as $item) {
		$url = Router::url($item['href'], true);
?>
<url>
  <loc><?=$url?></loc>
  <changefreq>daily</changefreq>
</url>
<?
	}
	foreach($aCategories as $article) {
		$url = SiteRouter::url($article);
?>
<url>
  <loc><?=$url?></loc>
  <changefreq>daily</changefreq>
</url>
<?
	}
	
	foreach($aSubcategories as $subcategories) {
		foreach($subcategories as $article) {
		$url = SiteRouter::url($article);
?>
<url>
  <loc><?=$url?></loc>
  <changefreq>daily</changefreq>
</url>
<?
		}
	}
?>
</urlset>
