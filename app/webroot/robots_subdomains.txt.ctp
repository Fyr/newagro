User-agent: *
Disallow: /
Allow: /$
Allow: /contacts
Allow: /sitemap
<?
    foreach($articles as $article) {
        $http_subdomain = 'http://'.$subdomain.'.'.Configure::read('domain.url');
		$url = str_replace($http_subdomain, '', SiteRouter::url($article)); // remove subdomain from URL
?>
Allow: <?=$url?>

<?
    }
?>
Sitemap: http://<?=$subdomain.'.'.Configure::read('domain.url')?>/sitemap.xml
Host: <?=$subdomain.'.'.Configure::read('domain.url')?>
