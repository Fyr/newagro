<?=$this->element('admin_title', array('title' => __('Settings').': '.__('Contacts')))?>
<div class="span8 offset2">
<?
	echo $this->PHForm->create('Settings');
	echo $this->element('admin_content');

	echo $this->PHForm->input('address');
	echo $this->PHForm->input('phone1', array('class' => 'input-large'));
	echo $this->PHForm->input('phone2', array('class' => 'input-large'));
	echo $this->PHForm->input('email', array('class' => 'input-large'));
	echo $this->PHForm->input('skype', array('class' => 'input-large'));

	echo $this->element('admin_content_end');
	echo $this->element('Form.btn_save');
	echo $this->PHForm->end();
?>
</div>