User-agent: *
Disallow: /
<?
    $http_subdomain = 'http://'.$subdomain.'.'.Configure::read('domain.url');
    $url = str_replace($http_subdomain, '', SiteRouter::url($category)); // remove subdomain from URL
?>
Allow: <?=$url?>

Sitemap: http://<?=$subdomain?>.agromotors.ru/sitemap.xml
Host: <?=$subdomain?>.agromotors.ru