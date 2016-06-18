<? echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?
    $aURL = array(
        '/pages/show/about-us',
        '/contacts'
    );
    foreach($aURL as $url) {
        $url = 'http://'.Configure::read('domain.url').$url;
        echo $this->element('sitemap_url', compact('url'));
    }
?>
</urlset>
