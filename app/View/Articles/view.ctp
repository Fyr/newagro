<?
    if ($objectType === 'SiteArticle') {
        $this->Html->css(array('/Icons/css/icons'), array('inline' => false));
    }

	if ($objectType == 'SectionArticle') {

		if (isset($currSubcat)) {
			$breadcrumbs = array(
				__('Home') => '/',
				$category['SectionArticle']['title'] => SiteRouter::url($category),
				$article['SectionArticle']['title'] => ''
			);
		} else {
			$breadcrumbs = array(
				__('Home') => '/',
				$category['SectionArticle']['title'] => '',
			);
			//$this->ObjectType->getTitle('view', $objectType) => ''
		}
	} else {
		$route = array('controller' => 'Articles', 'action' => 'index', 'objectType' => $objectType);
		if (in_array($objectType, array('News', 'Offer')) && ($filial = Configure::read('domain.filial'))) {
			$route['filial'] = $filial;
		}
		$breadcrumbs = array(
			__('Home') => '/',
			$this->ObjectType->getTitle('index', $objectType) => $route,
			$this->ObjectType->getTitle('view', $objectType) => ''
		);
	}
	echo $this->element('bread_crumbs', array('aBreadCrumbs' => $breadcrumbs));
	echo $this->element('title', array('title' => $article[$objectType]['title']));
	if ($objectType == 'Dealer') {
		echo $this->element('/Article/view_Dealer', compact('article'));
	} else {
?>
<?
            if ($objectType === 'SiteArticle') {
?>
        <div class="views">
            <span class="">Просмотров: </span><?=$article[$objectType]['views']?>
            <span class="icon-color icon-preview"></span><br/>
            <span class="">Рейтинг: </span><?=$article[$objectType]['views']?>
        </div>
        <div class="time">
            <span class="icon clock" style="margin-right: 3px; position: relative; top: -1px; "></span><?=$this->PHTime->niceShort($article[$objectType]['created'])?>
        </div>
        <div class="author">
            <span class="">Автор: </span><?=$article[$objectType]['author']?>
        </div>

<?
            }
?>

<div class="block main clearfix">
	<div class="article">
		<?=$this->ArticleVars->body($article)?>
	</div>
</div>
<?
	}
?>
