<?
	$indexUrl = array(
		'controller' => 'Products', 
		'action' => 'index',
		'objectType' => 'Product',
	);
	$title = 'Результаты поиска';
	$aBreadCrumbs = array();
	$relatedArticle = array();
	if (isset($subcategory)) {
		$title = $subcategory['Subcategory']['title'];
		$aBreadCrumbs = array(
			__('Home') => '/',
			$this->ObjectType->getTitle('index', 'Product') => $indexUrl,
			$subcategory['Category']['title'] => SiteRouter::url(array('Category' => $subcategory['Category'])),
			$title => ''
		);
		$relatedArticle = $subcategory;
	} elseif (isset($category)) {
		$title = $category['Category']['title'];
		$aBreadCrumbs = array(
			__('Home') => '/',
			$this->ObjectType->getTitle('index', 'Product') => $indexUrl,
			$title => ''
		);
		$relatedArticle = $category;
	} else {
		$title = $this->ObjectType->getTitle('index', 'Product');
		$aBreadCrumbs = array(
			__('Home') => '/',
			$this->ObjectType->getTitle('index', 'Product') => ''
		);
	}
	if ($aBreadCrumbs) {
		echo $this->element('bread_crumbs', compact('aBreadCrumbs'));
	}
	echo $this->element('title', compact('title'));
	if (!$aArticles) {
?>
	<div class="block main clearfix">
		<b>Не найдено ни одного продукта</b>
		<p>
			Пож-ста, измените параметры поиска или нажмите
			<a href="<?=Router::url($indexUrl)?>">сюда</a>,
			чтобы просмотреть весь каталог продукции.
		</p>
	</div>
<?
	} else {
?>
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
			if ($article['Product']['brand_id'] && isset($aBrands[$brand_id])) {
				if (isset($directSearch) && $directSearch) {
					$catTitle = $article['Category']['title'].' &gt; '.$article['Subcategory']['title']; // $brands[$article['Product']['brand_id']]['Brand']['title']
?>
					<div class="brand"><small><?=$catTitle?></small></div>
<?
				}
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
<?
		if (isset($directSearch) && $directSearch) {
			// echo $this->element('pagination2', array('filterURL' => $aFilters['url']));
		} else {
			echo $this->element('paginate', array('objectType' => 'products'));
		}
?>

<?
	}

	if ($relatedArticle) {
		echo $this->ArticleVars->body($relatedArticle);
	}
?>
