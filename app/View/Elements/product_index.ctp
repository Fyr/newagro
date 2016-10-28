	<div class="catalogContent clearfix<?=(isset($directSearch) && $directSearch) ? ' brands' : ''?>">
<?
		foreach($aArticles as $article) {
			$this->ArticleVars->init($article, $url, $title, $teaser, $src, '130x100', $featured);
			$title = $article['Product']['code'].' '.$article['Product']['title_rus'];
			$brand_id = $article['Product']['brand_id'];
			if (!$src) {
				if (isset($aBrands[$brand_id])) {
					$src = $this->Media->imageUrl($aBrands[$brand_id], '130x100');
				}
			}
			
?>
			<a id="product_<?=$article['Product']['id']?>" class="block" href="<?=$url?>">
				<div class="top">
<?
			if (isset($directSearch) && $directSearch) {
				$catTitle = $article['Category']['title'].' &gt; '.$article['Subcategory']['title']; // $brands[$article['Product']['brand_id']]['Brand']['title']
?>
					<div class="brand"><small><?=$catTitle?></small></div>
<?
			}
?>
					<div class="title ellipsis"><?=$title?></div>
				</div>
				<div class="ava">
					<span class="icon <?=($article['Product']['active']) ? 'available' : 'noAvailable'?>"></span>
					<img src="<?=($src) ? $src : '/img/default_product100.png'?>" alt="<?=$title?>" />
				</div>
<?
			$price = 0;
			$prod_id = $article['Product']['id'];
			if (Configure::read('domain.zone') == 'ru') {
				if (isset($prices_ru[$prod_id])) {
					$price = $prices_ru[$prod_id]['value'];
				} elseif (isset($prices2_ru[$prod_id])) {
					$price = $prices2_ru[$prod_id]['value'];
				}
			} else {
				if (isset($prices_by[$prod_id])) {
					$price = $prices_by[$prod_id]['value'];
				}
			}
			if ($price) {
				echo '<div class="price">'.$this->element('price', compact('price')).'</div>';
			}
?>
			</a>
<?
		}
?>                            
	</div>
