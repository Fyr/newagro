<ul class="catalog" id="catalog">
<?
	foreach($aCategories as $id => $article) {
		$url = (isset($aSubcategories[$id])) ? 'javascript: void(0)' : SiteRouter::url($article);
?>
	<li id="cat-nav<?=$id?>">
        <a href="<?=$url?>" class="firstLevel"><span class="icon arrow"></span><?=$article['Category']['title']?></a>
<?
		if (isset($aSubcategories[$id])) {
?>
		<ul style="display: none">
<?
			foreach($aSubcategories[$id] as $subcat_id => $_article) {
				$url = SiteRouter::url($_article);
?>
            <li><a href="<?=$url?>"><span class="icon smallArrow"></span> <?=$_article['Subcategory']['title']?></a></li>
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