<?
	$aBreadCrumbs = array(__('Home') => '/', $aArticle['Page']['title'] => '');
	echo $this->element('bread_crumbs', compact('aBreadCrumbs'));
	echo $this->element('title', array('title' => $aArticle['Page']['title']));
?>
<div class="block main clearfix">
	<div class="article">
		<?=$this->ArticleVars->body($aArticle)?>
	</div>
</div>
