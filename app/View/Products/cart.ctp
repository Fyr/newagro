<?
$this->Html->css(array('grid', '/Icons/css/icons'), array('inline' => false));
$this->Html->script(array('cart', 'number_format', 'vendor/jquery/jquery.cookie'), array('inline' => false));

$aBreadCrumbs = array(__('Home') => '/', __('Cart') => '');
echo $this->element('bread_crumbs', compact('aBreadCrumbs'));
echo $this->element('title', array('title' => __('Cart')));

if ($cartItems) {
?>
<div class="block main clearfix">
	<table class="grid" width="100%" cellpadding="0" cellspacing="0">
		<thead>
		<tr>
			<th>N п/п</th>
			<th>Фото</th>
			<th>Бренд</th>
			<th width="50%">Код детали / Название</th>
			<th>Кол-во</th>
			<th>Цена</th>
			<th>Сумма</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
<?
	$class = '';
	// $total = 0;
	$aPrices = array();
	foreach($aProducts as $i => $article) {
		$this->ArticleVars->init($article, $url, $title, $teaser, $src, '130x100', $featured, $id);
		if (!($title = Hash::get($article, 'Product.title_rus'))) {
			$title = Hash::get($article, 'Product.title');
		}

		$brand_id = Hash::get($article, 'Product.brand_id');
		if (!$src) {
			if (isset($aBrands[$brand_id])) {
				$src = $this->Media->imageUrl($aBrands[$brand_id], '130x100');
			}
		}
		$class = ($class == 'odd') ? 'even' : 'odd';
		$aPrices[$id] = $this->Price->getPrice($article);
		// $total+= intval($cartItems[$id]);
		$asterix = ($aPrices[$id]) ? '' : '<span style="font-size: 14px; font-weight: bold;">*</span>';
?>
			<tr id="cart-item_<?=$id?>" class="gridRow <?=$class?>">
				<td align="right"><?=$asterix?><?=$i + 1?></td>
				<td>
					<a href="<?=$this->Html->url($url)?>">
						<img class="no-fancybox" src="<?=$src?>" alt="<?=$title?>" style="width: 50px"/>
					</a>
				</td>
				<td nowrap="nowrap"><?=Hash::get($aBrands[$brand_id], 'Brand.title')?></td>
				<td>
					<?=Hash::get($article, 'Product.code')?><br/>
					<?=$this->Html->link($title, $url)?>
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
<?=$this->element('title', array('title' => 'Ваши данные для заказа'))?>
	<div class="block main feedback">
		<p>
			<?=__('Fields with %s are mandatory.', '<span class="star">*</span>')?><br/>
		</p>
		<?
		echo $this->Form->create('SiteOrder');
		echo $this->Form->input('SiteOrder.username', array('label' => array('text' => '<span class="star">*</span> '.__('Your name'))));
		echo $this->Form->input('SiteOrder.email', array('label' => array('text' => '<span class="star">*</span>'.__('E-mail'))));
		echo $this->Form->input('SiteOrder.phone', array('type' => 'text', 'label' => array('text' => '<span class="star">*</span> Телефон')));
		echo $this->Form->input('SiteOrder.address', array('type' => 'textarea', 'label' => array('text' => '<span class="star">*</span> Адрес')));
		echo $this->Form->input('SiteOrder.comment', array('type' => 'textarea', 'label' => array('text' => 'Комментарий к заказу')));
		// echo $this->Form->input('captcha', array('type' => 'hidden', 'label' => array('text' => '<span class="star">*</span> '.__('Spam protection'))));
		echo $this->Form->button('Заказать', array('class' => 'submit', 'type' => 'submit', 'onclick' => "console.log('submit'); this.disabled=true; $('#SiteOrderCartForm').submit()"));
		echo $this->Form->end();
		?>
	</div>
<?
} else {
?>
<div class="block main clearfix">
	<p>
		Корзина пуста.<br />
		<br />
		<a href="/"><?=__('Back to home page')?></a>
	</p>
</div>
<?
}
?>
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
	var $tr, id, qty, total = 0;
	$('input[name="cart-qty"]').each(function(){
		qty = parseInt($(this).val());
		$tr = $(this).parent().parent();
		id = $tr.get(0).id.replace(/cart-item_/, '');
		$('.cart-price', $tr).html(price_format(prices[id], false));
		$('.cart-sum', $tr).html(price_format(prices[id] * qty, false));
		total+= prices[id] * qty;
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

	Cart_updateTotal();
});

</script>
