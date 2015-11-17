<ul class="menu clearfix">
<?
	foreach($aBottomLinks as $id => $item) {
		$class = ($id == $currLink) ? ' class="active"' : '';
?>
    <li<?=$class?>><a href="<?=$item['href']?>"><span class="icon smallArrow"></span><?=$item['title']?></a></li>
<?
	}
?>
</ul>