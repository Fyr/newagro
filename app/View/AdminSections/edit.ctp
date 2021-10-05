<div class="span8 offset2">
<?
    $id = $this->request->data('Section.id');
    $title = ($id) ? __('Edit section') : __('Create section');
    echo $this->element('admin_title', compact('title'));
    echo $this->PHForm->create('Section');
    echo $this->element('admin_content');
    echo $this->PHForm->hidden('id');
    echo $this->PHForm->input('title');
    echo $this->PHForm->input('sorting');
	echo $this->element('admin_content_end');
	echo $this->element('Form.form_actions', array('backURL' => $this->Html->url(array('action' => 'index'))));
    echo $this->PHForm->end();
?>
</div>
