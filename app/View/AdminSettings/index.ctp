<?=$this->element('admin_title', array('title' => __('Settings').': '.__('System')))?>
<div class="span8 offset2">
<?
	echo $this->PHForm->create('Settings');
	$aTabs = array(
		__('General') => 
			$this->PHForm->input('admin_email', array('class' => 'input-large',  'label' => array('class' => 'control-label', 'text' => 'Email администратора')))
			.$this->PHForm->input('contacts_email', array('class' => 'input-large',  'label' => array('class' => 'control-label', 'text' => 'Email для сообщений')))
			.$this->PHForm->input('orders_email', array('class' => 'input-large',  'label' => array('class' => 'control-label', 'text' => 'Email для заказов')))
			.$this->PHForm->input('sectionizer', array('options' => array(0 => 'Категории', 1 => 'Категории + Статьи'))),
		__('Contacts') => 
			$this->PHForm->input('address', array('type' => 'textarea'))
			.$this->PHForm->input('phone1', array('class' => 'input-large'))
			.$this->PHForm->input('phone2', array('class' => 'input-large'))
			.$this->PHForm->input('email', array('class' => 'input-large'))
			.$this->PHForm->input('skype', array('class' => 'input-large'))
	);
	echo $this->element('admin_tabs', compact('aTabs'));
	// echo $this->element('admin_content_end');
	echo $this->element('Form.btn_save');
	echo $this->PHForm->end();
?>
</div>