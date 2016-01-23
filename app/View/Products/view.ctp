<?
	$this->Html->css('grid', array('inline' => false));
	$title = $article['Product']['code'].' '.$article['Product']['title_rus'];
	
	$indexUrl = array(
		'controller' => 'Products', 
		'action' => 'index',
		'objectType' => 'Product',
	);
	
	$aBreadCrumbs = array(
		__('Home') => '/',
		$this->ObjectType->getTitle('index', 'Product') => $indexUrl,
		$article['Category']['title'] => SiteRouter::url(array('Category' => $article['Category']))
	);
	$subcatSlug = Hash::get($article, 'Subcategory.slug');
	if ($subcatSlug) {
		$aBreadCrumbs[$article['Subcategory']['title']] = SiteRouter::url(array('Subcategory' => $article['Subcategory'], 'Category' => $article['Category']));
	}
	$aBreadCrumbs[$this->ObjectType->getTitle('view', 'Product')] = '';

	echo $this->element('bread_crumbs', compact('aBreadCrumbs'));
	echo $this->element('title', compact('title'));
	$brand = $aBrands[$article['Product']['brand_id']];
	$alt = $article['Product']['title_rus'].' '.$article['Product']['detail_num'];
	
	$price_by = Configure::read('params.price_by');
	$price_ru = Configure::read('params.price_ru');
	$price2_ru = Configure::read('params.price2_ru');
?>
						<div class="block main clearfix">

<?
	if (isset($article['Media']) && $article['Media']) {
		foreach($article['Media'] as $media) {
			if ($media['ext'] == '.pdf') {
?>
	<div style="float: right">
		<a href="<?=$media['url_download']?>">Скачать <b><?=$media['file'].$media['ext']?></b></a>
	</div>
<?
				break;
			}
		}
	}
	if ($article['Product']['active']) {
?>
											<img class="floatR" src="/img/active_yes.png" alt="В наличии" />
<?
	} else {
?>
											<img class="floatR" src="/img/active_no.png" alt="Не на складе" />
<?
	}
?>
							<b><?=__('Brand')?></b> : <?=$brand['Brand']['title']?><br />
							<!--b><?=__('Type')?></b> : <?=$article['Category']['title']?><br /-->
<?
	$price = 0;
	if (Configure::read('domain.zone') == 'ru') {
		if (isset($aParamValues[$price_ru]) && $aParamValues[$price_ru]) {
			$price = $aParamValues[$price_ru]['ParamValue']['value'];
		} elseif (isset($aParamValues[$price2_ru]) && $aParamValues[$price2_ru]) {
			$price = $aParamValues[$price2_ru]['ParamValue']['value'];
		}
	} else {
		if (isset($aParamValues[$price_by]) && $aParamValues[$price_by]) {
			$price = $aParamValues[$price_by]['ParamValue']['value'];
		}
	}

	if ($price) {
?>
							<b><?=__('Price')?></b> : <?=$this->element('price', compact('price'))?><br />
<?
	}
?>
							<div style="margin-top: 20px">
								<div class="article">
									<?=$this->ArticleVars->body($article)?>
								</div>
							</div>
							<!-- div class="line" style="width: 100%"></div-->
						</div>
						<div class="gallery">
<?
	if (isset($article['Media']) && $article['Media']) {
		foreach($article['Media'] as $media) {
			$src = $this->Media->imageUrl(array('Media' => $media), '400x'); // $this->Media->imageUrl($media['object_type'], $media['id'], '400x', $media['file'].$media['ext'].'.png');
			$orig = $media['url_img']; // $this->Media->imageUrl($media['object_type'], $media['id'], 'noresize', $media['file'].$media['ext'].'.png');
?>
								<div class="image" style="text-align:center">
									<a class="fancybox" href="<?=$orig?>" rel="photoalobum"><img alt="<?=$alt?>" src="<?=$src?>" /></a>
								</div>
<?
		}
	} else {
		$src = $this->Media->imageUrl($brand, '400x');
?>
								<div class="image" style="text-align:center">
									<img alt="<?=$alt?>" src="<?=($src) ? $src : '/img/default_product.jpg'?>" style="width: 400px" />
								</div>

<?
	}
?>
						</div>

<?
	/*
	if ($article['Product']['show_detailnum']) {
		$aParamValues[''] = array(
			'ParamValue' => array('param_id' => '', 'value' => $article['Product']['detail_num']),
			'Param' => array('title' => 'Номер запчасти', 'param_type' => 4)
		);
		ksort($aParamValues);
	}
	*/
	if ($article['PMFormData'] && $article['PMFormField']) {
?>
	<h3><?=__('Tech.parameters')?></h3>
	<table class="grid" width="100%" cellpadding="0" cellspacing="0">
	<thead>
	<tr>
		<th width="30%"><?=__('Parameter')?></th>
		<th><?=__('Value')?></th>
	</tr>
	</thead>
	<tbody>
<?
	if ($article['Product']['show_detailnum']) {
?>
	<tr class="gridRow td">
		<td nowrap="nowrap" align="right"><?=__('Spare number')?></td>
		<td><b><?=$article['Product']['detail_num']?></b></td>
	</tr>
<?
	}
	$class = '';
	
	foreach($article['PMFormField'] as $field) {
		if (in_array($field['id'], array($price_by, $price_ru, $price2_ru))) {
			continue;
		}
		$value = $article['PMFormData']['fk_'.$field['id']];
		if ($field['id'] == Configure::read('params.motor')) {
			$value = str_replace(',', ', ', $value);
		}
		if ($value) {
			$class = ($class == 'odd') ? 'even' : 'odd';
?>
	<tr class="gridRow <?=$class?> td">
		<td nowrap="nowrap" align="right"><?=$field['label']?></td>
		<td><b><?=$value?></b></td>
	</tr>
<?
		}
	}
?>
	</tbody>
	</table>
<?
	}
?>
	<br />
	<a href="/zapchasti/"><?=__('Back to catalog')?></a>
<?
/*
	if ($aSimilar) {
?>
	<div class="line" style="width: 100%"></div>
	<h3><?=__('Similar products')?></h3>
<?
		foreach($aSimilar as $article) {
			$this->ArticleVars->init($article, $url, $title, $teaser, $src, '150x', $featured);
?>
	<a href="<?=$url?>"><?=$title?></a><br />
<?
		}
	}
	*/
?>
