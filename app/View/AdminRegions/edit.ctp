<div class="span8 offset2">
<?
	$id = $this->request->data('Region.id');
	$title = $this->ObjectType->getTitle(($id) ? 'edit' : 'create', 'Region');
	echo $this->element('admin_title', compact('title'));

	echo $this->PHForm->create('Region');
	echo $this->PHForm->hidden('id');
	echo $this->element('admin_content');
	echo $this->Html->link(
		'<i class="icon icon-map-marker"></i> Конструктор карт <i class="icon icon-chevron-right"></i>',
		'https://tech.yandex.ru/maps/tools/constructor/',
		array('class' => 'btn btn-mini pull-right', 'target' => '_blank', 'escape' => false)
	);
	echo $this->PHForm->input('title');
	echo $this->PHForm->input('map_js', array('type' => 'textarea', 'label' => array('text' => 'Код карты', 'class' => 'control-label')));

	echo $this->element('admin_content_end');
	echo $this->element('Form.form_actions', array('backURL' => $this->Html->url(array('action' => 'index'))));
	echo $this->PHForm->end();
?>
</div>
