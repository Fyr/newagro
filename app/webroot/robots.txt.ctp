User-agent: *
Disallow: /admin/
Disallow: /js/
Disallow: /files/
Disallow: /products/
Allow: /files/catalog
<?
    foreach($subdomainCategories as $category) {
        $http_subdomain = 'http://'.$category['Category']['slug'].'.'.Configure::read('domain.url');
		$url = str_replace($http_subdomain, '', SiteRouter::url($category)); // remove subdomain from URL
?>
Disallow: <?=$url?>

<?
    }

    foreach($articles as $article) {
        $http_domain = 'http://'.Configure::read('domain.url');
		$url = str_replace($http_domain, '', SiteRouter::url($article)); // remove subdomain from URL
?>
Disallow: <?=$url?>

<?
    }
?>
Sitemap: http://agromotors.ru/sitemap.xml
Host: agromotors.ru