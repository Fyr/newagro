<?
	$this->Html->css(array('regions-map'), array('inline' => false));
?>
<?=$this->element('title', array('title' => $contentArticle['Page']['title']))?>
<div id="preload" style="display: none">
<?
	foreach($aRegions as $id => $region) {
		echo $this->Html->image('/img/regions/h'.$id.'.png');
	}
?>
</div>
<div id="wrap-map" style="display: none">
	<div class="region-map map">
		<div id="map-all" class="map"></div>
		<div class="map-region" style="position: relative; text-align: center; display: none; ">
			<a class="small-map" href="javascript:;" title="Назад к карте"><img src="/img/regions/regions_all.png" alt="Назад к карте" /></a>
			<span style="display: inline-block; position: relative;">
<?
	foreach($aRegions as $id => $region) {
		echo $this->Html->image('/img/regions/region'.$id.'.png', array('id' => 'region'.$id, 'class' => 'region', 'style' => 'display: none'));
		if (isset($aSubdomains[$id])) {
			foreach($aSubdomains[$id] as $subdomain) {
				$url = 'http://'.$subdomain['name'].'.'.Configure::read('domain.url');
				echo $this->Html->link($this->Html->image('/img/pointer.png'), $url, array(
					'escape' => false,
					'id' => 'marker'.$id,
					'class' => 'marker rm'.$id,
					'title' => $subdomain['title'],
					'style' => 'display: none; position: absolute; left: '.$subdomain['marker_x'].'px; top: '.$subdomain['marker_y'].'px'
				));
			}
		}
	}
?>
			</span>
		</div>
		<div class="map-nav">
			<img class="map" src="/img/blank.gif" alt="Карта регионов России" usemap="#regions_nav" />
			<map name="regions_nav">
<?
	/*
				<area class="r1" shape="poly" coords="9,302,19,303,22,291,28,291,38,300,46,317,46,328,41,334,41,347,34,347,27,327" href="#1" title="Северо-Кавказский регион" />
				<area class="r2" shape="poly" coords="9,302,19,303,22,291,28,291,38,300,46,317,69,315,66,290,77,281,57,257,45,264,41,261,2,277,5,300" href="#2" alt="Южный регион" />
				<area class="r4" shape="poly" coords="122,214,130,205,130,221,149,218,175,225,150,259,140,297,150,315,97,287,77,280,62,262,62,256,71,251,71,241,80,235,78,231,106,220,119,221" href="#2" alt="Центральный регион" >
				<area class="r5" shape="poly" coords="62,260,57,257,45,264,41,261,28,238,32,219,23,214,43,191,47,191,51,183,62,188,68,186,92,198,95,206,104,206,115,214,122,214,120,221,97,222,79,230,80,236,70,243,70,252,62,256" href="#2" alt="Центральный регион" >
				<area class="r6" shape="poly" coords="43,191,47,191,51,183,62,188,68,186,92,198,95,206,104,206,115,214,122,214,130,205,130,221,149,218,175,225,191,201,219,187,220,172,204,139,248,117,252,107,224,110,157,144,130,107,39,179" href="#2" alt="Центральный регион" >
				<area class="r7" shape="poly" coords="175,225,191,201,219,187,220,172,242,145,266,148, 279,225,276,259,242,255,226,275,210,270,203,294,140,297,150,259" href="#2" alt="Центральный регион" >
				<area class="r8" shape="poly" coords="266,148,279,225,276,259,242,255,226,275,210,270,203,294,214,312,235,318,243,343,285,360,342,349,347,335,413,345,456,319,456,286,407,242,383,263,368,221,358,221,350,179,363,158,350,138,350,110,300,70" href="#2" alt="Центральный регион" >
				<area class="r9" shape="poly" coords="456,286,407,242,383,263,368,221,358,221,350,179,363,158,350,138,422,108,395,89,427,74,430,100,545,0,558,166,10,185,546,153,508,193,509,239,537,219,590,270,545,345,478,283" href="#2" alt="Центральный регион" >
	*/
	foreach($aRegions as $id => $region) {
		echo $this->Html->tag('area', '', array(
			'alt' => $region['title'],
			'title' => $region['title'],
			'class' => 'r',
			'data-region' => $id,
			'shape' => 'poly',
			'coords' => $region['area_map'],
			'href' => 'javascript:;',
		));
		if ($region['marker_x'] && $region['marker_y']) {
			echo $this->Html->link('', 'javascript:;', array(
				'escape' => false,
				'data-region' => $id,
				'class' => 'r region-point',
				'title' => $region['marker_title'],
				'style' => 'left: ' . $region['marker_x'] . 'px; top: ' . $region['marker_y'] . 'px;'
			));
		}
	}
?>
			</map>
		</div>
	</div>
</div>
<div class="block main article clearfix">
	<?=$this->ArticleVars->body($contentArticle)?>
</div>
<script type="text/javascript">
function calcProp(k, prop) {
	return Math.round(prop.replace(/px/, '') * k) + 'px';
}
function resizeMapWidget(context) {
	var rect = $('#regions-map').get(0).getBoundingClientRect();
	var k = rect.width / 615;
	$('.map, .rh', context).each(function(){
		var props = {
			width: calcProp(k, $(this).css('width')),
			height: calcProp(k, $(this).css('height'))
		};
		$(this).css(props);
	});
	$('.rh, .region-point, .marker', context).each(function(){
		var props = {
			left: calcProp(k, $(this).css('left')),
			top: calcProp(k, $(this).css('top'))
		};
		$(this).css(props);
	});
	$('area', context).each(function(){
		var coords = $(this).attr('coords').split(',');
		for(var i = 0; i < coords.length; i++) {
			coords[i] = Math.round(k * coords[i]);
		}
		$(this).attr('coords', coords.join(','));
	});
	$('img.region', context).each(function(){
		this.width = Math.round(k * this.width);
		this.height = Math.round(k * this.height);
	});
}
$(function(){
	$('#wrap-map area.r').each(function(){
		$('#wrap-map .region-map').append('<div class="rh rh' + $(this).data('region') + '" />');
	});
	if ($(window).width() < 870) {
		resizeMapWidget('#wrap-map');
	}
	$('#regions-map').html($('#wrap-map').html());
	$('#wrap-map').html('');

	$('.r').mouseenter(function () {
		$('.rh' + $(this).data('region')).show();
	}).mouseleave(function () {
		$('.rh' + $(this).data('region')).hide();
	}).click(function(){
		var regionID = $(this).data('region');
		$('.map-nav').hide();

		$('#map-all').addClass('small-map');

		$('.map-region .region').hide();
		$('.marker').hide();
		$('.map-region #region' + regionID).show();
		$('.rm' + regionID).show();

		setTimeout(function(){
			$('.map-region').fadeIn(500);
		}, 250);
	});
	$('.small-map').click(function(){
		$('.map-region').fadeOut(500);
		$('#map-all').show();
		setTimeout(function(){
			$('#map-all').removeClass('small-map');
			setTimeout(function(){
				$('.map-nav').show();
			}, 500);
		}, 500);
	});
});
</script>