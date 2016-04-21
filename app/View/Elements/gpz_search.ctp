<?
	if (isset($gpzError)) {
		echo $this->element('admin_content');
?>
<div style="color: #f00; padding: 20px 0;">
	<b>Ошибка!</b><br />
	<?=$gpzError?>
</div>
<?
		echo $this->element('admin_content_end');
	} elseif (isset($gpzData) && !$gpzData) {
		echo $this->element('admin_content');
?>
<div style="padding: 20px 0;">
	По данному запросу результатов не найдено
</div>
<?
		echo $this->element('admin_content_end');
	} elseif (isset($gpzData) && $gpzData) {
?>
<table class="grid" width="100%" cellpadding="0" cellspacing="0">
	<thead>
	<tr class="first table-gradient">
		<th>Логотип</th>
		<th>Производитель</th>
		<th>Номер</th>
		<th>Изображение</th>
		<th>Наименование</th>
		<th>Ссылка</th>
	</tr>
	</thead>
	<tbody>
<?
		foreach ($gpzData as $row) {
?>
		<tr class="grid-row">
			<td align="center">
				<?=($row['brand_logo']) ? $this->Html->image($row['brand_logo'], array('class' => 'brand-logo')) : ''?>
			</td>
			<td>
				<?=$row['brand']?>
			</td>
			<td nowrap="nowrap"><?=$row['partnumber']?></td>
			<td align="center">
<?
			if ($row['image']) {
?>
			<a class="fancybox" href="<?=$row['image']?>">
				<?=$this->Html->image($row['image'], array('class' => 'product-img', 'alt' => $row['partnumber'].' '.$row['title']))?>
			</a>
<?
			}
?>
			</td>
			<td>
				<?=$row['title']?>
			</td>
			<td align="center">
				<a href="<?=$this->Html->url(array('action' => 'price', '?' => array('brand' => $row['brand'], 'number' => $row['partnumber'])))?>">подробнее</a>
			</td>
		</tr>
<?
		}
?>

	</tbody>
</table>
<?
	}
?>
