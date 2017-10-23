<b>Получен новый заказ:</b><br />
N заказа: 1000<?=$order['SiteOrder']['id']?><br />
Дата: <?=$order['SiteOrder']['created']?><br />
<br />
<b>Контакты заказчика:</b><br/>
Имя: <?=$this->request->data('SiteOrder.username')?><br/>
Телефон: <?=$this->request->data('SiteOrder.phone')?><br/>
Email: <?=h($this->request->data('SiteOrder.email'))?><br/>
<u>Адрес</u>: <br />
<?=nl2br(h($this->request->data('SiteOrder.address')))?><br/>
<u>Комментарий к заказу</u>:<br/>
<?=nl2br(h($this->request->data('SiteOrder.comment')))?><br/>
<br/>
<b>Заказанные позиции:</b><br/>
<table border="0" cellpadding="0" cellspacing="0">
<thead>
	<tr>
		<th>N п/п</th>
		<th>Бренд</th>
		<th>Код детали</th>
		<th width="50%">Название</th>
		<th>Кол-во</th>
	</tr>
</thead>
<tbody>
<?
	$class = '';
	$total = 0;
	foreach($aProducts as $i => $article) {
		$this->ArticleVars->init($article, $url, $title, $teaser, $src, '130x100', $featured, $id);
		$brand_id = Hash::get($article, 'Product.brand_id');
		if (!($title = Hash::get($article, 'Product.title_rus'))) {
			$title = Hash::get($article, 'Product.title');
		}
		$class = ($class == 'odd') ? 'even' : 'odd';
		$total+= intval($cartItems[$id]);
?>
	<tr class="<?=$class?>">
		<td align="right"><?=$i + 1?></td>
		<td nowrap="nowrap"><?=Hash::get($aBrands[$brand_id], 'Brand.title')?></td>
		<td><?=Hash::get($article, 'Product.code')?></td>
		<td><?=$this->Html->link($title, $url)?></td>
		<td align="right"><?=$cartItems[$id]?></td>
	</tr>
<?
	}
	$class = ($class == 'odd') ? 'even' : 'odd';
?>
	<tr class="<?=$class?>">
		<td colspan="4" align="right"><b>Всего:</b></td>
		<td align="right"><?=$total?></td>
	</tr>
</tbody>
</table>