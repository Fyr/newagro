<?
$this->Html->css(array('grid', '/Icons/css/icons'), array('inline' => false));
$this->Html->script(array('cart', 'number_format', 'vendor/jquery/jquery.cookie'), array('inline' => false));

$aBreadCrumbs = array(__('Home') => '/', __('Cart') => '');
echo $this->element('bread_crumbs', compact('aBreadCrumbs'));
echo $this->element('title', array('title' => __('Cart')));

$loginLink = $this->Html->link(__('User%sarea', ' '), array('controller' => 'user', 'action' => 'login'));
$registerLink = $this->Html->link(__('register yourself'), array('controller' => 'pages', 'action' => 'register'));
?>
    <p>Войдите в <?=$loginLink?>, чтобы ускорить оформление заказа</p>
    <p>Если у вас нет учетной записи, <?=$registerLink?>, чтобы получить доступ в Личный кабинет</p>
<?
if ($cartItems) {
    echo $this->element('cart_items');
    echo $this->element('title', array('title' => 'Ваши данные для заказа'));
?>
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
    echo $this->element('cart_empty');
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
