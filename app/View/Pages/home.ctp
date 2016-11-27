<?
	$this->Html->css(array('regions-map', 'regions'), array('inline' => false));
?>
<?=$this->element('title', array('title' => $contentArticle['Page']['title']))?>
<div class="region-map map">
	<div class="map" style="background: url(/img/regions/regions_all.png) no-repeat; position: absolute;"></div>
	<div style="position: absolute; z-index: 3;">
		<img class="map" src="/img/blank.gif" alt="Карта регионов России" usemap="#regions_nav" />
		<map name="regions_nav">
			<area class="r1" shape="poly" coords="9,302,19,303,22,291,28,291,38,300,46,317,46,328, 41,334,41,347,34,347,27,327" href="#1" alt="Северо-Кавказский регион" />
			<area class="r2" shape="poly" coords="9,302,19,303,22,291,28,291,38,300,46,317,69,315,66,290,77,281,57,257,45,264,41,261,2,277,5,300" href="#2" alt="Южный регион" />
			<area class="r4" shape="poly" coords="122,214,130,205,130,221,149,218,175,225,150,259,140,297,150,315,97,287,77,280,62,262,62,256,71,251,71,241,80,235,78,231,106,220,119,221" href="#2" alt="Центральный регион" >
			<area class="r5" shape="poly" coords="62,260,57,257,45,264,41,261,28,238,32,219,23,214,43,191,     47,191,51,183,62,188,68,186,92,198,95,206,104,206,115,214,122,214,    120,221,97,222,79,230,80,236,70,243,70,252,62,256" href="#2" alt="Центральный регион" >
			<area class="r6" shape="poly" coords="43,191,47,191,51,183,62,188,68,186,92,198,95,206,104,206,115,214,122,214,   130,205,130,221,149,218,175,225,   191,201,219,187,220,172,        204,139,248,117,252,107,224,110,157,144,130,107,39,179" href="#2" alt="Центральный регион" >
			<area class="r7" shape="poly" coords="175,225,191,201,219,187,220,172,242,145,   266,148, 279,225,276,259,242,255,226,275,210,270,   203,294, 140,297,150,259" href="#2" alt="Центральный регион" >
			<area class="r8" shape="poly" coords="266,148,279,225,276,259,242,255,226,275,210,270,203,294, 214,312, 235,318,243,343,285,360,342,349,347,335,413,345,456,319,   456,286, 407,242,383,263,368,221,358,221,350,179,363,158,350,138,   350,110,300,70" href="#2" alt="Центральный регион" >
			<area class="r9" shape="poly" coords="456,286,407,242,383,263,368,221,358,221,350,179,363,158,350,138,422,108,395,89,427,74,430,100,545,0,558,166,10,185,546,153,508,193,509,239,537,219,590,270,545,345,478,283" href="#2" alt="Центральный регион" >
		</map>
	</div>

</div>
<div class="region-nav"></div>
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
	for(var i = 1; i <= 9; i++) {
		(function(i) {
			$('.region-map').append('<div class="rh' + i + '" />');
			$('.r' + i).mouseenter(function () {
				$('.rh' + i).show();
				$('.region-nav').html('Северо-Кавказский регион');
			}).mouseleave(function () {
				$('.rh' + i).hide();
				$('.region-nav').html('');
			});
		}(i));
	}
});
</script>