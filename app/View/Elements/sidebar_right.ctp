<?
	if (isset($aSlot[4])) {
		foreach($aSlot[4] as $banner) {
			$min_w = 228;
			echo $this->element('banner', compact('banner', 'min_w'));
		}
	}
	if ($featuredEvents) {
		echo $this->element('sbr_block', array('title' => 'Новости', 'content' => $this->element('sb_news', array('aArticles' => $featuredEvents))));
	}
	if ($featuredOffers) {
		echo $this->element('sbr_block', array('title' => 'Акции', 'content' => $this->element('sb_news', array('aArticles' => $featuredOffers))));
	}
	if (isset($aSlot[5])) {
		foreach($aSlot[5] as $banner) {
			$min_w = 228;
			echo $this->element('banner', compact('banner', 'min_w'));
		}
	}
?>
