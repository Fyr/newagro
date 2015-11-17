<div class="dealer-info">
<?
		if ($article['Company']['address']) {
?>
	<div>
		<span>Адрес:</span>
		<?=$article['Company']['address']?>
	</div>	
<?
		}
?>
<?
		if ($article['Company']['phones']) {
?>
	<div>
		<span>Тел.:</span>
		<?=$article['Company']['phones']?>
	</div>
<?
		}
?>
<?
		if ($article['Company']['work_time']) {
?>
	<div>
		<span>Время работы:</span>
		<?=$article['Company']['work_time']?>
	</div>
<?
		}
?>
<?
		if ($article['Company']['site_url']) {
?>
	<div>
		<span>Сайт:</span>
		<a href="http://<?=$article['Company']['site_url']?>"><?=$article['Company']['site_url']?></a>
	</div>
<?
		}
?>
<?
		if ($article['Company']['email']) {
?>
	<div>
		<span>Email:</span>
		<a href="mailto:<?=$article['Company']['email']?>"><?=$article['Company']['email']?></a>
	</div>
<?
		}
?>
</div>