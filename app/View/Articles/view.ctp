<?
	echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
		__('Home') => '/',
		$this->ObjectType->getTitle('index', $objectType) => array('controller' => 'Articles', 'action' => 'index', 'objectType' => $objectType),
		$this->ObjectType->getTitle('view', $objectType) => ''
	)));
	echo $this->element('title', array('title' => $article[$objectType]['title']));
	if ($objectType == 'Dealer') {
		echo $this->element('/Article/view_Dealer', compact('article'));
	} else {
?>
<div class="block main clearfix">
	<div class="article">
		<?=$this->ArticleVars->body($article)?>
	</div>
</div>
<?
	}
?>