User-agent: *
Disallow: /admin/
Disallow: /js/
Disallow: /files/
Disallow: /products/
Allow: /files/catalog
Sitemap: http://<?=Configure::read('domain.url')?>/sitemap.xml
Host: <?=Configure::read('domain.url')?>