<?
		foreach($aArticles as $article) {
			$this->ArticleVars->init($article, $url, $title, $teaser, $src, '190x145', $featured);
			$title = $article['Product']['code'].' '.$article['Product']['title_rus'];
			$brand_id = $article['Product']['brand_id'];
			if (!$src) {
				if (isset($aBrands[$brand_id])) {
					$src = $this->Media->imageUrl($aBrands[$brand_id], '190x145');
				}
			}
			
?>
			<a id="product_<?=$article['Product']['id']?>" class="catalog__block" href="<?=$url?>">
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
			$price = $this->Price->getPrice($article);
			if ($price) {
				echo '<div class="price">'.$this->Price->format($price).'</div>';
			}
?>
			</a>
<?
		}
?>                            
