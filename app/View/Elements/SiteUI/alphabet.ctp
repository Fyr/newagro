<div class="pull-left alphabet" style="clear:both">
	<div>
		<a href="#">0-9</a>&nbsp;
<?
	for($i = 65; $i <= 90; $i++) {
?>
		<a href="#"><?=chr($i)?></a>
<?
	}
?>
	</div>
	<div>
<?
	$ru = 'АБВГДЕЖЗИЙКЛМНОПРСТУФХЦЧШЩЫЭЮЯ';
	for($i = 0; $i <= mb_strlen($ru); $i++) {
?>
		<a href="#"><?=mb_substr($ru, $i, 1)?></a>
<?
	}
?>

	</div>
</div>