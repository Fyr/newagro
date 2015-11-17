<?
	$title = $this->ObjectType->getTitle('index', $objectType);
	echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
		__('Home') => '/',
		$title => ''
	)));
	echo $this->element('title', compact('title'));
	foreach($aArticles as $article) {
		if ($objectType == 'Dealer') {
			echo $this->element('/Article/index_Dealer', compact('article'));
		} else {
			$this->ArticleVars->init($article, $url, $title, $teaser, $src, '150x', $featured, $id);
?>
                        <div class="block clearfix">
<?
			if ($src) {
?>
                            <a href="<?=$url?>"><img src="<?=$src?>" alt="<?=$title?>" class="thumb"/></a>
<?
			}
			if ($objectType == 'News' || $objectType == 'Offer') {
?>
                            <div class="time"><span class="icon clock"></span><?=$this->PHTime->niceShort($article[$objectType]['created'])?></div>
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
	}
?>
<?=$this->element('paginate')?>
