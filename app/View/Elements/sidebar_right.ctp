<?
	if (isset($aSlot[4])) {
		foreach($aSlot[4] as $banner) {
			$min_w = 228;
			echo $this->element('banner', compact('banner', 'min_w'));
		}
	}
	if ($featuredEvents) {
		echo $this->element('sbr_block', array('title' => __('News'), 'content' => $this->element('sb_news', array('aArticles' => $featuredEvents))));
	}
	if ($featuredOffers) {
?>
	<div class="sbr-offers">
		<?=$this->element('sbr_block', array('title' => __('Hot Offers'), 'content' => $this->element('sb_news', array('aArticles' => $featuredOffers))))?>
	</div>
<?
	}
	if (isset($aSlot[5])) {
		foreach($aSlot[5] as $banner) {
			$min_w = 228;
			echo $this->element('banner', compact('banner', 'min_w'));
		}
	}
?>
