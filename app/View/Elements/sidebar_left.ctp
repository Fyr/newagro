<?
	// echo $this->element('sbl_block', array('class' => 'types', 'content' => $this->element('sb_types')));
	foreach($aSections as $section_id => $title) {
		echo $this->element('sbl_block', array('title' => $title, 'content' => $this->element('sb_types', compact('section_id'))));
	}
	if (Configure::read('Settings.sectionizer')) {
		foreach($aSections2 as $section_id => $title) {
			echo $this->element('sbl_block', array('title' => $title, 'content' => $this->element('sb_types', array(
				'section_id' => $section_id,
				'aCategories' => $aCategories2,
				'aSubcategories' => $aSubcategories2
			))));
		}
	}
	if (isset($aSlot[3])) {
		foreach($aSlot[3] as $banner) {
			$min_w = 228;
			echo $this->element('banner', compact('banner', 'min_w'));
		}
	}
	// echo $this->element('tag_cloud');
?>
