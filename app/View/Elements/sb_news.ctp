<?
	$this->ArticleVars->init($article, $url, $title, $teaser, $src, '80x');
?>
<div class="time"><span class="icon clock"></span><?=$this->PHTime->niceShort($article['News']['created'])?></div>
<a href="<?=$url?>" class="title"><?=$title?></a>
<div class="description"><p><?=$teaser?></p></div>
<div class="more">
    <?=$this->element('more', compact('url'))?>
</div>