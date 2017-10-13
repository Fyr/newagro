<?
	$style = ($cartItems) ? '' : 'display: none;';
?>
<div id="cart" class="sbr-offers" style="<?=$style?>">
	<div class="headBlock">
		<div class="text">Корзина</div>
		<span class="corner"></span>
	</div>
	<div class="block">
		<div style="padding: 5px">
			<img src="/img/cart_checked.png" alt="" style="height: 22px; position: relative; top: 5px;" />
			Заказано <a class="cart_items" href="javascript:" onclick="Cart.checkout()"><?=count($cartItems)?></a> позиции
		</div>
		<div class="more">
			<a href="javascript:" onclick="Cart.checkout()">Оформить заказ</a>
		</div>
	</div>
</div>