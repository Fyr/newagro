<?
	$this->Html->script(array('cart', 'vendor/jquery/jquery.cookie'), array('inline' => false));
?>
<?=$this->element('title', array('title' => 'Ваш заказ принят'))?>
<div class="block main clearfix">
	<p>
		<b>Спасибо за Ваш заказ!</b><br />
		Наш менеджер свяжется с вами в ближайшее время.<br />
		<br />
		<b>Ваши данные для заказа:</b><br />
		N заказа: 1000<?=$order['SiteOrder']['id']?><br />
		Дата: <?=$order['SiteOrder']['created']?><br />
		На имя: <?=$order['SiteOrder']['username']?><br />
		<br />
		Просмотр заказа N <?=$this->Html->link('1000'.$order['SiteOrder']['id'], array('controller' => 'user', 'action' => 'orderview', $order['SiteOrder']['id']))?>
	</p>
</div>
<script>
$(function(){
	Cart.setData({});
});
</script>
