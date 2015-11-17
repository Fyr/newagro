<?
	// echo $this->element('sbl_block', array('class' => 'types', 'content' => $this->element('sb_types')));
	echo $this->element('sbl_block', array('title' => 'Каталог', 'content' => $this->element('sb_types', compact('aTypes'))));
	if (isset($aSlot[3])) {
		foreach($aSlot[3] as $banner) {
			$min_w = 228;
			echo $this->element('banner', compact('banner', 'min_w'));
		}
	}
	// echo $this->element('tag_cloud');
?>
