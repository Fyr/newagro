<style>
	.map-images {margin-top: 20px;}
</style>
<div class="span8 offset2">
<?
	$id = $this->request->data('Marker.id');
	$title = $this->ObjectType->getTitle(($id) ? 'edit' : 'create', 'Marker');
	echo $this->element('admin_title', compact('title'));
	echo $this->PHForm->create('Marker');
	echo $this->PHForm->hidden('id');
	echo $this->PHForm->hidden('marker_x');
	echo $this->PHForm->hidden('marker_y');

	$map_images = '';
	foreach($aRegions as $id => $region) {
		$map_images.= $this->Html->image("/img/regions/region{$id}.png", array('class' => 'map-region', 'id' => 'region'.$id, 'style' => 'display: none'));
	}
	$map_images.= $this->Html->image('/img/pointer.png', array('id' => 'marker', 'style' => 'position: absolute; left: 0px; top; 0px;'));

	$aTabs = array(
		__('General') =>
			$this->PHForm->input('title').
			$this->PHForm->input('region_id', array(
				'options' => $aRegions,
				'label' => array('text' => __('Region'), 'class' => 'control-label'),
				'onclick' => 'onSelectRegion()',
			)).
			$this->PHForm->input('url'),
		__('Map') =>
			$this->Html->div('', 'Кликните по карте, чтобы установить маркер данного субдомена').
			$this->Html->div('map-images', $map_images, array('style' => 'position: relative;'))
	);
	echo $this->element('admin_tabs', compact('aTabs'));
	echo $this->element('Form.form_actions', array('backURL' => $this->Html->url(array('action' => 'index'))));
	echo $this->PHForm->end();
?>
</div>
<script>
function onSelectRegion() {
	var id = $('#MarkerRegionId').val();
	$('.map-region').hide();
	$('#region' + id).show();
}
function cssPx(e, prop, val) {
	var px = parseInt($(e).css(prop).replace(/px/, ''));
	if (typeof(val) != 'undefined') {
		$(e).css(prop, val + 'px');
	}
	return px;
}
function placeMarker(pos) {
	cssPx('#marker', 'left', pos.x);
	cssPx('#marker', 'top', pos.y);
}
$(function(){
	onSelectRegion();
	placeMarker({x: <?=intval($this->request->data('Marker.marker_x'))?>, y: <?=intval($this->request->data('Marker.marker_y'))?>});
	$('.map-region').click(function(e){
		var div = $('.map-images').get(0).getBoundingClientRect();
		var pos = {x: e.pageX - parseInt(div.left) - 12, y: e.pageY - parseInt(div.top) - $('#marker').get(0).height};
		placeMarker(pos);
		$('#MarkerMarkerX').val(pos.x);
		$('#MarkerMarkerY').val(pos.y);
	});
});
</script>