<?
    if ($objectType === 'SiteArticle') {
        $this->Html->css(array('/Icons/css/icons'), array('inline' => false));
    }
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
			if (in_array($objectType, array('News', 'Offer', 'SiteArticle'))) {
?>
                            <div class="time">
                                <span class="icon clock"></span><?=$this->PHTime->niceShort($article[$objectType]['created'])?>
                            </div>
<?
			}
?>
<?
            if ($objectType === 'SiteArticle') {
?>
                            <div class="views">
                                <span class="">Просмотров: </span><?=$article[$objectType]['views']?>
                                <span class="icon-color icon-preview"></span><br/>
                                <span class="">Рейтинг: </span><?=$article[$objectType]['views']?>
                            </div>
                            <div class="author">
                                <span class="">Автор: </span><?=$article[$objectType]['author']?>
                            </div>

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
