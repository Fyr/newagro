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
	$brand_id = $article['Product']['brand_id'];
	$brand = ($article['Product']['is_fake']) ? $aFakeBrands[$brand_id] : $aBrands[$brand_id];
	$brandTitle = $brand['Brand']['title'];
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

	$price = $this->Price->getPrice($article, false);
    $finalPrice = $this->Price->getPrice($article, false, $aDiscounts);
	$brandDiscount = 0;
	if ($price !== $finalPrice) { // brand discount for this item is already applied
        $brandTitle.= ' (<span class="discount-price">-'.$aDiscounts[$brand_id].'%</span>)';
	}
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
							<b><?=__('Brand')?></b> : <?=$brandTitle?><br />
<?
	if ($finalPrice) {
	    $showPrice = ($price === $finalPrice)
	        ? $this->Price->format($finalPrice, true)
	        : $this->Html->div('old-price', $this->Price->format($price, false)).' '.$this->Html->div('discount-price', $this->Price->format($finalPrice, true));
?>
							<b><?=__('Price')?></b> : <?=$showPrice?><br />
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
	$zone = Configure::read('domain.zone');
	if (isset($article['Media']) && $article['Media']) {
		foreach($article['Media'] as $i => $media) {
			$_alt = (isset($media['alt_'.$zone]) && $media['alt_'.$zone]) ? $media['alt_'.$zone] : $alt.' Вид '.($i + 1);
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
			$value = $article['PMFormData']['fk_'.$field['id']];
			if ($field['id'] == Configure::read('params.motor')) {
				if ($article['Product']['is_fake']) {
					$value = '';
				} else {
					$value = str_replace(',', ', ', $value);
				}
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
		echo $this->Html->div('catalogContent clearfix', $this->element('product_index', array('aArticles' => $aRelated)));
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
