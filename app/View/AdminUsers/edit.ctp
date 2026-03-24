<div class="span8 offset2">
<?
    $id = $this->request->data('AdminUser.id');
    $title = $this->ObjectType->getTitle(($id) ? 'edit' : 'create', $objectType);
    echo $this->element('admin_title', compact('title'));
    echo $this->PHForm->create('AdminUser');
	$aTabs = array(
		'General' => $this->element('/AdminUsers/admin_edit_User'),
	);
	echo $this->element('admin_tabs', compact('aTabs'));
	echo $this->element('Form.form_actions', array('backURL' => $this->Html->url(array('action' => 'index'))));
    echo $this->PHForm->end();
?>
</div>
