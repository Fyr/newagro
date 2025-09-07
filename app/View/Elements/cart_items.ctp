<?
    $this->Html->script(array('cart', 'number_format', '/Table/js/format', 'vendor/jquery/jquery.cookie'), array('inline' => false));
?>
<div class="block main clearfix">
	<table class="grid" width="100%" cellpadding="0" cellspacing="0">
		<thead>
		<tr>
			<th nowrap="nowrap">N п/п</th>
			<th>Фото</th>
			<th>Бренд</th>
			<th width="50%">Код детали / Название</th>
			<th nowrap="nowrap">Кол-во</th>
			<th>Цена</th>
			<th>Сумма</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
<?
	$class = '';
	$aPrices = array();
	foreach($aProducts as $i => $article) {
	    $class = ($class == 'odd') ? 'even' : 'odd';

		$this->ArticleVars->init($article, $url, $title, $teaser, $src, '130x100', $featured, $id);
		if (!($title = Hash::get($article, 'Product.title_rus'))) {
			$title = Hash::get($article, 'Product.title');
		}
        $code = Hash::get($article, 'Product.code');
		$brand_id = Hash::get($article, 'Product.brand_id');

		// detect image for product (fallback to brand logo)
		if (!$src) {
            $src = (isset($aBrands[$brand_id])) ? $this->Media->imageUrl($aBrands[$brand_id], '130x100') : '';
		}

		// detect brand title (fallback to fake brands)
        $brandTitle = Hash::get(
            (Hash::get($article, 'Product.is_fake')) ? $aFakeBrands[$brand_id] : $aBrands[$brand_id],
            'Brand.title'
        );

		$price = $this->Price->getPrice($article, $aDiscounts, false); // price is shown dynamically (JS)
		$finalPrice = $this->Price->getPrice($article, $aDiscounts);
		$brandDiscount = $this->Price->getBrandDiscount($article, $aDiscounts);
		if ($brandDiscount) { // brand discount for this item is already applied
		    $brandTitle.= '<br/>'.$this->Html->div('discount-price', -$brandDiscount.'%');
		}

		$aPrices[$id] = $price;
		$aFinalPrices[$id] = $finalPrice;
		$asterix = ($aPrices[$id]) ? '' : '<span style="font-size: 14px; font-weight: bold;">*</span>';
?>
			<tr id="cart-item_<?=$id?>" class="gridRow <?=$class?>">
				<td align="right"><?=$asterix?><?=$i + 1?></td>
				<td>
					<a href="<?=$this->Html->url($url)?>">
						<img class="no-fancybox" src="<?=$src?>" alt="<?=$title?>" style="width: 50px"/>
					</a>
				</td>
				<td nowrap="nowrap"><?=$brandTitle?></td>
				<td>
					<?=$this->Html->link($code.' '.$title, $url)?>
				</td>
				<td align="center">
					<input type="text" autocomplete="off" name="cart-qty" value="<?=$cartItems[$id]?>" style="width: 20px; text-align: center"
						   onclick="this.focus()" onfocus="this.select()" onkeyup="Cart_edit(<?=$id?>)" onchange="Cart_edit(<?=$id?>)"
					/>
				</td>
				<td class="cart-price" align="right" nowrap="nowrap"></td>
				<td class="cart-sum" align="right" nowrap="nowrap"></td>
				<td class="nowrap text-center">
					<a class="icon-color icon-delete" href="javascript:;" title="Удалить из корзины" onclick="Cart.remove(<?=$id?>)" style="display: inline-block"></a>
				</td>
			</tr>
<?
	}
	$class = ($class == 'odd') ? 'even' : 'odd';
?>
			<tr class="gridRow <?=$class?>">
				<td colspan="4" align="right" style="padding: 13px 5px"><b>Итого:</b></td>
				<td id="cart-total" colspan="3" align="right" nowrap="nowrap"></td>
				<td></td>
			</tr>
		</tbody>
	</table>
	Для позиций, помеченных знаком <span><b>*</b></span>, уточняйте цены у менеджера по продажам.
	<!--div class="more">
		<a href="#">Пересчитать</a>
	</div-->
</div>
<script>
function Cart_edit(id) {
	var cart = Cart.getData();
	var qty = $('#cart-item_' + id + ' input[name="cart-qty"]').val();
	if (isNaN(qty)) {
		alert('Введите целое кол-во!');
		return false;
	}
	if (!qty) {
		return false;
	}
	cart[id] = qty;
	Cart.setData(cart);
	Cart_updateTotal();
}

function Cart_updateTotal() {
	var $tr, id, qty, total = 0, price, finalPrice;
	$('input[name="cart-qty"]').each(function(){
		qty = parseInt($(this).val());
		$tr = $(this).parent().parent();
		id = $tr.get(0).id.replace(/cart-item_/, '');

		price = price_format(prices[id], false);
		finalPrice = price_format(finalPrices[id], false);
		showPrice = finalPrice;
        if (price !== finalPrice) {
            showPrice = Format.tag('span', { class: 'old-price' }, price) + '<br/>'
                + Format.tag('span', { class: 'discount-price' }, finalPrice);
        }
		$('.cart-price', $tr).html(showPrice);
		$('.cart-sum', $tr).html(price_format(finalPrices[id] * qty, false));
		total+= finalPrices[id] * qty;
	});
	$('#cart-total').html(price_format(total, true));
}

function price_format(number, lFull) {
	var price = number_format(number, decimals, dec_point, thousands_sep);
	if (lFull) {
		price = (prefix + price + postfix);
	}

	return price.replace(/\$P/ig, '<span class="rubl">₽</span>');
}

var decimals, dec_point, thousands_sep, prefix, postfix, prices;
$(function(){
	decimals = '<?=Configure::read('Settings.decimals')?>';
	dec_point = '<?=Configure::read('Settings.float_div')?>';
	thousands_sep = '<?=Configure::read('Settings.int_div')?>';
	prefix = '<?=Configure::read('Settings.price_prefix')?>';
	postfix = '<?=Configure::read('Settings.price_postfix')?>';
	prices = <?=json_encode($aPrices)?>;
    finalPrices = <?=json_encode($aFinalPrices)?>;
	Cart_updateTotal();
});

</script>
