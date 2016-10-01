<div class="span8 offset2">
<?
	$id = $this->request->data('Subdomain.id');
	$title = $this->ObjectType->getTitle(($id) ? 'edit' : 'create', 'Subdomain');
	echo $this->element('admin_title', compact('title'));
	echo $this->PHForm->create('Subdomain');
	echo $this->PHForm->hidden('id');
	$aTabs = array(
		__('General') =>
			$this->PHForm->input('name', array('label' => array('text' => __('Subdomain'), 'class' => 'control-label')))
			.$this->PHForm->input('sorting', array('class' => 'input-mini')),
		__('Contacts') =>
			$this->PHForm->input('address', array('type' => 'textarea'))
			.$this->PHForm->input('phone1', array('class' => 'input-large'))
			.$this->PHForm->input('phone2', array('class' => 'input-large'))
			.$this->PHForm->input('email', array('class' => 'input-large'))
			.$this->PHForm->input('skype', array('class' => 'input-large'))
	);
	echo $this->element('admin_tabs', compact('aTabs'));
	echo $this->element('Form.form_actions', array('backURL' => $this->Html->url(array('action' => 'index'))));
	echo $this->PHForm->end();
?>
</div>
