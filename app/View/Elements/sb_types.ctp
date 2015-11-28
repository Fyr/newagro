<ul class="catalog" id="catalog">
<?
	foreach($aCategories[$section_id] as $id => $article) {
		$objectType = $this->ArticleVars->getObjectType($article);
		$url = (isset($aSubcategories[$id])) ? 'javascript: void(0)' : SiteRouter::url($article);
?>
	<li id="cat-nav<?=$id?>">
        <a href="<?=$url?>" class="firstLevel"><span class="icon arrow"></span><?=$article[$objectType]['title']?></a>
<?
		if (isset($aSubcategories[$id])) {
?>
		<ul style="display: none">
<?
			foreach($aSubcategories[$id] as $subcat_id => $_article) {
				$objectType = $this->ArticleVars->getObjectType($_article);
				$url = SiteRouter::url($_article);
?>
            <li><a href="<?=$url?>"><span class="icon smallArrow"></span> <?=$_article[$objectType]['title']?></a></li>
<?
			}
?>
        </ul>
<?
		}
?>
    </li>
<?
	}
?>
</ul>
