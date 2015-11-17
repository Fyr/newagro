<div style="overflow: hidden; text-align: center;">
<?
if ($aBreadCrumbs) {
?>
<ul class="breadCrumbs clearfix">
<?
	foreach($aBreadCrumbs as $title => $url) {
		if ($url) {
?>
	<li><a href="<?=$url?>"><?=$title?></a></li>
<?
		} else {
?>
	<li><span><?=$title?></span></li>
<?
		}
	}
?>
</ul>
<?
}
?>
</div>