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
		if (intval(Hash::get($this->request->params, 'page')) < 2) {
			$relatedArticle = $subcategory;
		}
	} elseif (isset($category)) {
		$title = $category['Category']['title'];
		$aBreadCrumbs = array(
			__('Home') => $homeUrl,
			$title => ''
		);
		if (intval(Hash::get($this->request->params, 'page')) < 2) {
			$relatedArticle = $category;
		}
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
		$class = (isset($directSearch) && $directSearch) ? ' brands' : '';
		echo $this->Html->div('catalogContent clearfix'.$class, $this->element('product_index'));
		echo $this->element('paginate', array('objectType' => 'products'));
	}

	if ($relatedArticle) {
		if ($html = trim($this->ArticleVars->body($relatedArticle))) {
			echo '<br>';
			echo $this->Html->div('block main article clearfix', $html);
		}
	}
?>
