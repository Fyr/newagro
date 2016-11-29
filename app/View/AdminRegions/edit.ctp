<div class="span8 offset2">
<?
	$id = $this->request->data('Region.id');
	$title = $this->ObjectType->getTitle(($id) ? 'edit' : 'create', 'Region');
	echo $this->element('admin_title', compact('title'));

	echo $this->PHForm->create('Region');
	echo $this->PHForm->hidden('id');
	echo $this->PHForm->hidden('marker_x');
	echo $this->PHForm->hidden('marker_y');
	// echo $this->element('admin_content');
	$map_images = $this->Html->image('/img/regions/regions_all.png', array('class' => 'map-region'))
		.$this->Html->image('/img/point.png', array('id' => 'marker', 'style' => 'position: absolute; left: 0px; top; 0px;'));
	$aTabs = array(
		__('General') =>
			$this->PHForm->input('title')
			.$this->PHForm->input('marker_title', array('label' => array('class' => 'control-label', 'text' => 'Надпись маркера'))),
		__('Map') =>
			$this->Html->div('', 'Кликните по карте, чтобы установить маркер данного региона<br />
				Кликните <a href="javascript:;" onclick="delMarker()">сюда</a>, чтобы удалить маркер')
			.$this->Html->div('map-images', $map_images, array('style' => 'position: relative;'))
	);
	// echo $this->PHForm->input('title');
	//echo $this->PHForm->input('map_js', array('type' => 'textarea', 'label' => array('text' => 'Код карты', 'class' => 'control-label')));

	// echo $this->element('admin_content_end');
	echo $this->element('admin_tabs', compact('aTabs'));
	echo $this->element('Form.form_actions', array('backURL' => $this->Html->url(array('action' => 'index'))));
	echo $this->PHForm->end();
?>
</div>
<script>
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

	$('#RegionMarkerX').val(pos.x);
	$('#RegionMarkerY').val(pos.y);

	if (pos.x && pos.y) {
		$('#marker').show();
	} else {
		$('#marker').hide();
	}
}
function delMarker() {
	placeMarker({x: 0, y: 0});
}
$(function(){
	placeMarker({x: <?=intval($this->request->data('Region.marker_x'))?>, y: <?=intval($this->request->data('Region.marker_y'))?>});
	$('.map-region').click(function(e){
		var div = $('.map-images').get(0).getBoundingClientRect();
		var pos = {x: e.pageX - parseInt(div.left) - 8, y: e.pageY - parseInt(div.top) - 8};
		placeMarker(pos);
	});
});
</script>