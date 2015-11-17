<ul class="menu clearfix menuDesktop">
<?
	foreach($aMenu as $id => $menu) {
		$href = (isset($menu['submenu'])) ? 'javascript: void(0)' : $menu['href'];
?>
	<li class="<?=(($id == $currMenu) ? 'active' : '')?>">
		<a href="<?=$href?>"><span><?=$menu['title']?></span> </a>
<?
		if (isset($menu['submenu'])) {
?>
		<ul style="display: none">
<?
			foreach($menu['submenu'] as $i => $submenu) {
?>
			<li><a href="<?=$submenu['href']?>"><?=$submenu['title']?></a></li>
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
<ul class="menu menuMobile clearfix">
   <li>
       <a href="javascript: void(0)"><span>Меню</span></a>
       <ul style="display: none">
<?
	foreach($aMenu as $id => $menu) {
?>
	<li>
		<a href="<?=$menu['href']?>"><?=$menu['title']?></a>
	</li>
<?
	}
?>
      </ul>
   </li>
</ul>