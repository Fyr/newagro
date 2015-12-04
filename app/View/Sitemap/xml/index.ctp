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
	foreach($aCategories[0] as $article) {
		$url = SiteRouter::url($article, true);
?>
<url>
  <loc><?=$url?></loc>
  <changefreq>daily</changefreq>
</url>
<?
	}
	
	foreach($aSubcategories as $subcategories) {
		foreach($subcategories as $article) {
		$url = SiteRouter::url($article, true);
?>
<url>
  <loc><?=$url?></loc>
  <changefreq>daily</changefreq>
</url>
<?
		}
	}

	if (Configure::read('Settings.sectionizer')) {
		foreach($aSections2 as $section_id => $title) {
			foreach ($aCategories2[$section_id] as $id => $article) {
				$url = SiteRouter::url($article, true);
?>

<url>
	<loc><?=$url?></loc>
	<changefreq>daily</changefreq>
</url>
<?
				if (isset($aSubcategories2[$id])) {
					foreach ($aSubcategories2[$id] as $_article) {
						$url = SiteRouter::url($_article, true);
?>

<url>
<loc><?=$url?></loc>
<changefreq>daily</changefreq>
</url>
<?
					}
				}
			}
		}
	}
?>
</urlset>
