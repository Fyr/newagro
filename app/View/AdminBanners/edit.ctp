<div class="span8 offset2">
<?
	$id = $this->request->data('Banner.id');
	$title = $this->ObjectType->getTitle(($id) ? 'edit' : 'create', $objectType);
	
	echo $this->element('admin_title', compact('title'));
	echo $this->PHForm->create('Banner');
	$aTabs = array(
        'General' => $this->element('/AdminContent/admin_edit_Banner'),
    );
    if ($id) {
        $aTabs['Media'] = $this->element('Media.edit', array('object_type' => 'Banner', 'object_id' => $id));
    }
	
	echo $this->element('admin_tabs', compact('aTabs'));
	echo $this->element('Form.form_actions', array('backURL' => $this->ObjectType->getBaseURL('')));
	echo $this->PHForm->end();
?>
</div>