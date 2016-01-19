<ul class="catalog" id="catalog">
<?
	foreach($aCategories[$section_id] as $id => $article) {
		$objectType = $this->ArticleVars->getObjectType($article);
		$url = SiteRouter::url($article);
		$active = (isset($category) && $id == $category[$this->ArticleVars->getObjectType($category)]['id']) ? 'active' : '';
?>
	<li id="cat-nav<?=$id?>">
        <a href="<?=$url?>" class="firstLevel <?=$active?>"><span class="icon arrow"></span><?=$article[$objectType]['title']?></a>
<?
		if (isset($aSubcategories[$id])) {
			$style = ($active) ? '' : 'style="display: none"';
?>
		<ul <?=$style?>>
<?
			foreach($aSubcategories[$id] as $subcat_id => $_article) {
				$objectType = $this->ArticleVars->getObjectType($_article);
				$url = SiteRouter::url($_article);
				$active = (isset($currSubcat) && $currSubcat == $_article[$objectType]['id']) ? 'class="active"' : '';
?>
            <li><a <?=$active?> href="<?=$url?>"><span class="icon smallArrow"></span> <?=$_article[$objectType]['title']?></a></li>
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
