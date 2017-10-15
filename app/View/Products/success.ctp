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
		N заказа: <?=$order['SiteOrder']['id']?><br />
		Дата: <?=$order['SiteOrder']['created']?><br />
		На имя: <?=$order['SiteOrder']['username']?><br />
		<br />
		<a href="/"><?=__('Back to home page')?></a>
	</p>
</div>
