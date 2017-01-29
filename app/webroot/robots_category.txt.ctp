User-agent: *
Disallow: /
Allow: /$
Allow: /sitemap
Allow: /zapchasti
Sitemap: http://<?=$subdomain.'.'.Configure::read('domain.url')?>/sitemap.xml
Host: <?=$subdomain.'.'.Configure::read('domain.url')?>