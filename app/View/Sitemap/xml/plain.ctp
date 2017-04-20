<? echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?
    $aURL = array(
        array('controller' => 'pages', 'action' => 'show', 'about-us'),
        array('controller' => 'contacts', 'action' => 'index')
    );
    foreach($aURL as $url) {
        $url = $this->Html->url($url, true);
        echo $this->element('sitemap_url', compact('url'));
    }

    foreach($aRegions as $id => $title) {
        $url = $this->Html->url(array('controller' => 'pages', 'action' => 'region', $id), true);
        echo $this->element('sitemap_url', compact('url'));
    }

    // Generate URLs only for PDFs
    foreach($aCatalogFiles as $catalog) {
        foreach($catalog['Media'] as $media) {
            if ($media['media_type'] == 'raw_file') {
                $url = $this->Html->url(array('controller' => 'Catalog', 'action' => 'viewPdf', 'slug' => $catalog['Catalog']['slug']), true);
                echo $this->element('sitemap_url', compact('url'));
            }
        }
    }
?>
</urlset>
