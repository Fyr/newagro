<?
	$objectType = $this->ArticleVars->getObjectType($aArticle);
	if ($objectType == 'Page') {
		$aBreadCrumbs = array(__('Home') => '/', __('Repair') => '');
	} else {
		$aBreadCrumbs = array(
			__('Home') => '/',
			__('Repair') => $this->Html->url(array('controller' => 'Repair', 'action' => 'index')),
			$aArticle[$objectType]['title'] => ''
		);
	}
	echo $this->element('bread_crumbs', compact('aBreadCrumbs'));
	echo $this->element('title', array('title' => $aArticle[$objectType]['title']));
?>
	<div class="block main clearfix">
		<div class="article">
			<?=$this->ArticleVars->body($aArticle)?>
		</div>
	</div>
<?
	foreach($articles as $article) {
			$this->ArticleVars->init($article, $url, $title, $teaser, $src, '150x', $featured, $id);
?>
	<div class="block clearfix">
<?
		if ($src) {
?>
		<a href="<?=$url?>"><img src="<?=$src?>" alt="<?=$title?>" class="thumb"/></a>
<?
		}
?>
		<a href="<?=$url?>" class="title"><?=$title?></a>
		<div class="description"><?=$teaser?></div>
		<div class="more">
			<?=$this->element('more', compact('url'))?>
		</div>
	</div>
<?
	}
?>
