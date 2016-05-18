<? echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?
	foreach($aArticles as $article) {
		$object_type = $article['Article']['object_type'];
		$article[$object_type] = $article['Article'];
		unset($article['Article']);
		$url = SiteRouter::url($article, true);
?>
<url>
  <loc><?=$url?></loc>
  <changefreq>daily</changefreq>
</url>
<?
	}
?>
</urlset>
