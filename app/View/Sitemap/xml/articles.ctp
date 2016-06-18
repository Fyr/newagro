<? echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?
    if ($this->request->objectType == 'RepairArticle') {
        $url = Router::url(array('controller' => 'Repair', 'action' => 'index'), true);
    } else {
        $url = Router::url(array('controller' => 'Articles', 'action' => 'index', 'objectType' => $this->request->objectType), true);
    }
    echo $this->element('sitemap_url', compact('url'));
    foreach($aArticles as $article) {
        $url = SiteRouter::url($article, true);
        echo $this->element('sitemap_url', compact('url'));
    }
?>
</urlset>