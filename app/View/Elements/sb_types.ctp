<ul class="catalog" id="catalog">
<?
	foreach($aTypes['type_'] as $type) {
		$url = (isset($aTypes['type_'.$type['id']])) ? 'javascript: void(0)' : SiteRouter::catUrl('products', $type);
?>
	<li id="cat-nav<?=$type['id']?>">
        <a href="<?=$url?>" class="firstLevel"><span class="icon arrow"></span><?=$type['title']?></a>
<?
		if (isset($aTypes['type_'.$type['id']])) {
?>
		<ul style="display: none">
<?
			foreach($aTypes['type_'.$type['id']] as $subtype) {
				$url = SiteRouter::catUrl('products', $subtype);
?>
            <li><a href="<?=$url?>"><span class="icon smallArrow"></span> <?=$subtype['title']?></a></li>
<?
			}
?>
        </ul>
<?
		}
?>
    </li>
<?
	}
?>
</ul>