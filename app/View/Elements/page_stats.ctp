<?
	$aMedia = $article['MediaTypes'];
	$url = SiteRouter::url($article);
?>
<div class="page-stats">
	<span><?=__('Updated')?>: <?=$this->PHTime->niceShort($article['Product']['modified'])?></span>
	<span><?=__('Views')?>: <?=$article['Product']['views']?></span>
	<span><?=__('Images')?>: <?=(isset($aMedia['image'])) ? count($aMedia['image']) : '-'?></span>
	<span><?=__('Logotypes in vector')?>: <?=(isset($aMedia['bin_file'])) ? $this->Html->link(count($aMedia['bin_file']), $url.'#vector') : '-'?></span>
</div>