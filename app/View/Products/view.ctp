<?
	$this->Html->css('grid', array('inline' => false));
	$this->Html->script(array('cart', 'vendor/jquery/jquery.cookie'), array('inline' => false));
	$title = $article['Product']['code'].' '.$article['Product']['title_rus'];
	
	$indexUrl = array(
		'controller' => 'Products', 
		'action' => 'index',
		'objectType' => 'Product',
	);
	
	$aBreadCrumbs = array(
		__('Home') => 'http://'.Configure::read('domain.url'),
		// $this->ObjectType->getTitle('index', 'Product') => $indexUrl,
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

	$price_by = Configure::read('params.price_by');
	$price2_by = Configure::read('params.price2_by');
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
		$src = '/img/active_yes.png';
		$alt = 'В наличии';
	} else {
		$src = '/img/active_no.png';
		$alt = 'Не на складе';
	}
	$lShowCart = (isset($cartItems[$article['Product']['id']]));
	$cartQty = ($lShowCart) ? $cartItems[$article['Product']['id']] : '';
?>

							<div class="floatR" style="width: 250px;">
								<img class="no-fancybox" src="<?=$src?>" alt="<?=$alt?>" style="height: 35px;" />
								<div class="check_cart" style="<?=($lShowCart) ? '' : 'display: none;'?>">
									<div class="more">
										<a href="javascript:" onclick="Cart.checkout()">
											<img class="no-fancybox" src="/img/cart_checked.png" alt="" style="" />&nbsp;В корзине: <span class="in-cart-qty"><?=$cartQty?></span>
										</a>
									</div>
								</div>

								<input type="text" class="cart-qty" name="qty" value="1" onfocus="this.select()" style="<?=($lShowCart) ? 'display: none;' : ''?>">
								<div class="add_cart" style="<?=($lShowCart) ? 'display: none;' : ''?>">
									<div class="more">
										<a href="javascript:" onclick="Cart.add(<?=$article['Product']['id']?>)">
											<img class="no-fancybox" src="/img/cart_add.png" alt="" style="" />&nbsp;Купить
										</a>
									</div>
								</div>
							</div>
							<b><?=__('Brand')?></b> : <?=$brand['Brand']['title']?><br />
							<!--b><?=__('Type')?></b> : <?=$article['Category']['title']?><br /-->
<?
	$price = 0;
	$aParamFields = Hash::combine($article['PMFormField'], '{n}.id', '{n}');
	if (Configure::read('domain.zone') == 'ru') {
		if (isset($aParamFields[$price_ru]) && $aParamFields[$price_ru]) {
			$price = $aParamFields[$price_ru]['ParamValue']['value'];
		} elseif (isset($aParamFields[$price2_ru]) && $aParamFields[$price2_ru]) {
			$price = $aParamFields[$price2_ru]['ParamValue']['value'];
		}
	} elseif (Configure::read('domain.zone') == 'by') {
		$price = (isset($aParamFields[$price_by])) ? floatval(Hash::get($article, 'PMFormData.fk_'.$price_by)): 0;
		$price2 = (isset($aParamFields[$price2_by])) ? floatval(Hash::get($article, 'PMFormData.fk_'.$price2_by)): 0;
		$price = ($price2) ? $price2 : $price;
	}

	if ($price) {
?>
							<b><?=__('Price')?></b> : <?=$this->element('price', compact('price'))?><br />
<?
	}
?>

							<!-- div class="line" style="width: 100%"></div-->
						</div>
						<div class="gallery">
<?
	$title_rus = $article['Product']['title_rus'];
	$code = $article['Product']['code'];
	$alt = (Configure::read('domain.zone') == 'ru') ? $code.' '.$title_rus : $title_rus.' '.$code;
	if (isset($article['Media']) && $article['Media']) {
		foreach($article['Media'] as $i => $media) {
			$_alt = (isset($media['alt']) && $media['alt']) ? $media['alt'] : $alt.' Вид '.($i + 1);
			$src = $this->Media->imageUrl(array('Media' => $media), '400x'); // $this->Media->imageUrl($media['object_type'], $media['id'], '400x', $media['file'].$media['ext'].'.png');
			$orig = $media['url_img']; // $this->Media->imageUrl($media['object_type'], $media['id'], 'noresize', $media['file'].$media['ext'].'.png');
?>
								<div class="image" style="text-align:center">
									<a class="fancybox" href="<?=$orig?>" rel="photoalobum"><img alt="<?=$_alt?>" title="<?=$_alt?>" src="<?=$src?>" /></a>
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
			if (in_array($field['id'], array($price_by, $price2_by, $price_ru, $price2_ru))) {
				continue;
			}
			$value = $article['PMFormData']['fk_'.$field['id']];
			if ($field['id'] == Configure::read('params.motor')) {
				$value = str_replace(',', ', ', $value);
			}
			if ($value) {
				$class = ($class == 'odd') ? 'even' : 'odd';
?>
	<tr class="gridRow <?=$class?>">
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
	if ($aRelated) {
		echo $this->Html->tag('h3', __('Related products'));
		echo $this->element('product_index', array('aArticles' => $aRelated));
	}
?>
	<br />
	<a href="/zapchasti/"><?=__('Back to catalog')?></a>
	<div style="margin-top: 20px">
		<div class="article">
			<?=$this->ArticleVars->body($article)?>
		</div>
	</div>
<script>
$(function(){
	$('.cart-qty').click(function(){
		$(this).focus();
		$(this).select();
	});
});
</script>