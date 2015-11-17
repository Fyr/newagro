<style type="text/css">
.dealer-info {
    font-size: 1em;
    margin-left: 0px;
}
</style>
<div class="block main clearfix">
<?
	$this->ArticleVars->init($article, $url, $title, $teaser, $src, '200x');
	if ($src) {
?>
	<img src="<?=$src?>" alt="<?=$title?>" style="float: right; margin: 0 0 10px 10px" />
<?
	}
?>
	<?=$this->element('dealer_details', array('article' => $article))?>
	<div class="clear"></div>
	<?=$this->ArticleVars->body($article)?>
</div>
