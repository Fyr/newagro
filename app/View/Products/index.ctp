<?
	$this->Html->css('grid', array('inline' => false));

	$homeUrl = 'http://'.Configure::read('domain.url');
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
			__('Home') => $homeUrl,
			$subcategory['Category']['title'] => SiteRouter::url(array('Category' => $subcategory['Category'])),
			$title => ''
		);
		$relatedArticle = $subcategory;
	} elseif (isset($category)) {
		$title = $category['Category']['title'];
		$aBreadCrumbs = array(
			__('Home') => $homeUrl,
			$title => ''
		);
		$relatedArticle = $category;
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
		echo $this->element('product_index');
		/*
		if (isset($directSearch) && $directSearch) {
			// echo $this->element('pagination2', array('filterURL' => $aFilters['url']));
		} else {
		}
		*/
		echo $this->element('paginate', array('objectType' => 'products'));
	}

	if (isset($gpzData) || isset($gpzError)) {
		echo '<br/><br/>'.$this->element('title', array('title' => 'Региональные склады'));
		echo $this->Html->div('block main clearfix', $this->element('gpz_search'));
	}

	if ($html = trim($this->ArticleVars->body($relatedArticle))) {
		echo '<br>';
		echo $this->Html->div('block main article clearfix', $html);
	}
?>
