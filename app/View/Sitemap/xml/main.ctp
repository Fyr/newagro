<? echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

	<url>
		<loc><?=Router::url(array(
				'controller' => 'Articles',
				'action' => 'index',
				'objectType' => 'News',
			), true)?></loc>
		<changefreq>daily</changefreq>
	</url>

	<url>
		<loc><?=Router::url(array(
				'controller' => 'Products',
				'action' => 'index',
				'objectType' => 'Product',
			), true)?></loc>
		<changefreq>daily</changefreq>
	</url>

	<url>
		<loc><?=Router::url(array(
				'controller' => 'Repair',
				'action' => 'index',
				//'objectType' => 'RepairArticle'
			), true)?></loc>
		<changefreq>daily</changefreq>
	</url>

	<url>
		<loc><?=Router::url(array(
				'controller' => 'Articles',
				'action' => 'index',
				'objectType' => 'Offer',
			), true)?></loc>
		<changefreq>daily</changefreq>
	</url>

	<url>
		<loc><?=Router::url(array(
				'controller' => 'Articles',
				'action' => 'index',
				'objectType' => 'Brand',
			), true)?></loc>
		<changefreq>daily</changefreq>
	</url>

	<url>
		<loc><?=Router::url(array(
				'controller' => 'Articles',
				'action' => 'index',
				'objectType' => 'Motor',
			), true)?></loc>
		<changefreq>daily</changefreq>
	</url>

	<url>
		<loc><?=Router::url(array(
				'controller' => 'Articles',
				'action' => 'index',
				'objectType' => 'Dealer',
			), true)?></loc>
		<changefreq>daily</changefreq>
	</url>

	<url>
		<loc><?=Router::url(array('controller' => 'Contacts', 'action' => 'index'), true)?></loc>
		<changefreq>daily</changefreq>
	</url>
	<url>
		<loc><?=Router::url(array('controller' => 'Catalog', 'action' => 'index'), true)?></loc>
		<changefreq>daily</changefreq>
	</url>
<?
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
/*
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
*/
?>
</urlset>
