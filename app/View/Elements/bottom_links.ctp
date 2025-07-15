<ul class="menu clearfix">
<?
	$class = "";
	foreach($aBottomLinks as $id => $item) {
		$class = ($id == $currLink) ? 'active' : '';
		if ($id == 'privacy' || $id == 'policy') {
			$class.= ' small';
		}
?>
    <li class="<?=trim($class)?>"><a href="<?=$item['href']?>"><span class="icon smallArrow"></span><?=$item['title']?></a></li>
<?
	}
?>
</ul>