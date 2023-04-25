<ul class="menu menuMobile clearfix menuBurger">
   <li>
       <a href="javascript: void(0)"><span>&equiv;</span></a>
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
<?
	if ($enPage) {
		$domainUrl = HTTP . Configure::read('domain.url');
		if ($isEN) {
			$classEN = array('class' => 'currLang');
			$classRU = array();
		} else {
			$classRU = array('class' => 'currLang');
			$classEN = array();
		}
?>
<span class="floatR langSwitch">
	<?=$this->Html->link('EN', $enPage, $classEN)?> | <?=$this->Html->link('RU', $domainUrl, $classRU)?>
</span>
<?
	}
?>
<ul class="menu clearfix menuDesktop" style="">
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
       <a href="<?=$aMenu['contacts']['href']?>"><span><?=$aMenu['contacts']['title']?></span></a>
   </li>
</ul>
