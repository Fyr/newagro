<div class="dealer-info">
<?
		if ($article['DealerTable']['address']) {
?>
	<div>
		<span>Адрес:</span>
		<?=$article['DealerTable']['address']?>
	</div>	
<?
		}
?>
<?
		if ($article['DealerTable']['phones']) {
?>
	<div>
		<span>Тел.:</span>
		<?=$article['DealerTable']['phones']?>
	</div>
<?
		}
?>
<?
		if ($article['DealerTable']['work_time']) {
?>
	<div>
		<span>Время работы:</span>
		<?=$article['DealerTable']['work_time']?>
	</div>
<?
		}
?>
<?
		if ($article['DealerTable']['site_url']) {
?>
	<div>
		<span>Сайт:</span>
		<a href="http://<?=$article['DealerTable']['site_url']?>"><?=$article['DealerTable']['site_url']?></a>
	</div>
<?
		}
?>
<?
		if ($article['DealerTable']['email']) {
?>
	<div>
		<span>Email:</span>
		<a href="mailto:<?=$article['DealerTable']['email']?>"><?=$article['DealerTable']['email']?></a>
	</div>
<?
		}
?>
</div>