<?=$this->element('admin_title', array('title' => __('Settings').': '.__('System')))?>
<div class="span8 offset2">
<?
	echo $this->PHForm->create('Settings');
	echo $this->element('admin_content');
	echo $this->PHForm->input('admin_email', array('class' => 'input-large'));
	echo $this->PHForm->input('sectionizer', array('options' => array(0 => 'Категории', 1 => 'Категории + Статьи')));

	echo $this->element('admin_content_end');
	echo $this->element('Form.btn_save');
	echo $this->PHForm->end();
?>
</div>