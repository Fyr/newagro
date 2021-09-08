<div class="span8 offset2">
<?
    $id = $this->request->data('Messenger.id');
    $title = ($id) ? __('Edit messenger') : __('Create messenger');
    echo $this->element('admin_title', compact('title'));

    echo $this->PHForm->create('Messenger');

    echo $this->element('admin_content');
    echo $this->PHForm->hidden('id');
    echo $this->PHForm->input('active');
    echo $this->PHForm->input('type', array('options' => $aTypeOptions));
    echo $this->PHForm->input('uid', array('label' => array('class' => 'control-label', 'text' => __('UID'))));
    echo $this->PHForm->input('title');
    echo $this->PHForm->input('sorting');
    echo $this->PHForm->input('used');

	echo $this->element('admin_content_end');
	echo $this->element('Form.form_actions', array('backURL' => $this->Html->url(array('action' => 'index'))));

    echo $this->PHForm->end();
?>
</div>
