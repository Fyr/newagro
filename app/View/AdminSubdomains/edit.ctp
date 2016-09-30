<div class="span8 offset2">
<?
	$id = $this->request->data('Subdomain.id');
	$title = $this->ObjectType->getTitle(($id) ? 'edit' : 'create', 'Subdomain');
	echo $this->element('admin_title', compact('title'));
	echo $this->PHForm->create('Subdomain');
	echo $this->element('admin_content');
	echo $this->PHForm->hidden('id');
	echo $this->PHForm->input('name');
	echo $this->PHForm->input('sorting');
	echo $this->element('admin_content_end');
	echo $this->element('Form.form_actions', array('backURL' => $this->Html->url(array('action' => 'index'))));
	echo $this->PHForm->end();
?>
</div>
