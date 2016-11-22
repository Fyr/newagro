<?=$this->element('title', array('title' => Hash::get($region, 'Region.title')))?>
<div class="block main article clearfix">
<?
	if ($map = Hash::get($region, 'Region.map_js')) {
		echo $this->Html->div('', $map);
	}
	if ($aSubdomains) {

		$aLinks = array();
		foreach($aSubdomains as $subdomain) {
			$url = 'http://'.$subdomain['Subdomain']['name'].'.'.Configure::read('domain.url');
			$aLinks[] = $this->Html->link($subdomain['Subdomain']['title'], $url, array('target' => '_blank', 'title' => 'Перейти на сайт представителя'));
		}
?>
		<h3>Представительства в регионе</h3>
		<p>
			<?=implode('<br />', $aLinks)?>
		</p>
<?
	} else {
?>
		<p>
			<br />
			Кликните <a href="<?=$this->Html->url(array('controller' => 'contacts', 'action' => 'index'))?>">сюда</a>, чтобы связаться с нами.
		</p>
<?
	}

?>
</div>