<?
    $this->Html->css(array('grid', '/Icons/css/icons'), array('inline' => false));
	$this->Html->script(array('cart', 'vendor/jquery/jquery.cookie'), array('inline' => false));
	// $order = $order['SiteOrder'];
?>
<?=$this->element('title', array('title' => 'Просмотр заказа N 1000'.$order['SiteOrder']['id']))?>
<div class="block main clearfix">
	<p>
		<b>Данные заказа:</b><br />
		Дата: <?=$order['SiteOrder']['created']?><br />
		На имя: <?=$order['SiteOrder']['username']?><br />
		Телефон: <?=$order['SiteOrder']['phone']?><br />
		<br/>
		<u>На адрес</u>:<br/>
		<?=nl2br($order['SiteOrder']['address'])?><br />
		<br />
<?
    if (trim($order['SiteOrder']['comment'])) {
?>
		<u>Комментарий к заказу</u>:<br/>
		<?=nl2br($order['SiteOrder']['comment'])?><br />
<?
    }
?>
    </p>
</div>

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
		</tr>
		</thead>
		<tbody>
<?
	$class = '';
	// $total = 0;
	$aPrices = array();
	foreach($aOrderDetails as $i => $row) {
	    $detail = $row['SiteOrderDetails'];
	    $product = $aProducts[$detail['product_id']];
		$this->ArticleVars->init($product, $url, $title, $teaser, $src, '130x100', $featured, $id);
		$code = Hash::get($product, 'Product.code');
		if (!($title = Hash::get($product, 'Product.title_rus'))) {
			$title = Hash::get($product, 'Product.title');
		}

		$class = ($class == 'odd') ? 'even' : 'odd';
		// $aPrices[$id] = $this->Price->getPrice($product);
		// $total+= intval($cartItems[$id]);
		// $asterix = ($aPrices[$id]) ? '' : '<span style="font-size: 14px; font-weight: bold;">*</span>';
?>
			<tr id="cart-item_<?=$id?>" class="gridRow <?=$class?>">
				<td align="center"><?=$i + 1?></td>
				<td>
<?
        $brand_id = Hash::get($product, 'Product.brand_id');
        if (!$src) {
            if (isset($aBrands[$brand_id])) {
                $src = $this->Media->imageUrl($aBrands[$brand_id], '130x100');
            }
        }
        if ($src) {
?>
					<a href="<?=$this->Html->url($url)?>">
						<img class="no-fancybox" src="<?=$src?>" alt="<?=$title?>" style="width: 50px"/>
					</a>
<?
        }
?>
				</td>
				<td nowrap="nowrap"><?=Hash::get($aBrands[$brand_id], 'Brand.title')?></td>
				<td><?=$this->Html->link($code.' '.$title, $url)?></td>
				<td align="right"><?=$detail['qty']?></td>
				<td class="cart-price" align="right" nowrap="nowrap"></td>
				<td class="cart-sum" align="right" nowrap="nowrap"></td>
			</tr>
<?
	}
	$class = ($class == 'odd') ? 'even' : 'odd';
?>
			<tr class="gridRow <?=$class?>">
				<td colspan="4" align="right" style="padding: 13px 5px"><b>Итого:</b></td>
				<td id="cart-total" colspan="3" align="right" nowrap="nowrap"></td>
			</tr>
		</tbody>
	</table>
		<!-- Перейти в <?=$this->Html->link(__('My orders'), array('controller' => 'user', 'action' => 'orders'))?> -->

<style>
img.no-fancybox { margin-top: 5px; }
</style>

