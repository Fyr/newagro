<?=$this->element('title', array('title' => $aArticle['Article']['title']))?>
<div class="block main clearfix">
	<?=$this->HtmlArticle->fulltext($aArticle['Article']['body'])?>
</div>
