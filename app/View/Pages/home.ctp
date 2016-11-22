<?
	$this->Html->css('regions', array('inline' => false));
?>
<?=$this->element('title', array('title' => $contentArticle['Page']['title']))?>
<div id="wrap-map" style="display: none">
	<div class="b_inner__map_wrap">
<?
	foreach($aRegions as $id => $title) {
?>
		<a href="<?=$this->Html->url(array('controller' => 'pages', 'action' => 'region', $id))?>" class="b_inner__map_fo<?=$id?>"><span><?=$title?></span></a>
<?
	}
?>
	</div>
</div>
<div class="block main article clearfix">
	<?=$this->ArticleVars->body($contentArticle)?>
</div>
<script type="text/javascript">
$(function(){
	$('#regions-map').html($('#wrap-map').html());
});
</script>