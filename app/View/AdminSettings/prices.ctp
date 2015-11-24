<?=$this->element('admin_title', array('title' => __('Settings').': '.__('Prices')))?>
<div class="span8 offset2">
<?
	echo $this->PHForm->create('Settings');
	echo $this->element('admin_content');

	echo $this->PHForm->input('price_prefix', array(
		'class' => 'input-small', 
		'label' => array('text' => 'Префикс цены', 'class' => 'control-label')
	));
	echo $this->PHForm->input('price_postfix', array(
		'class' => 'input-small', 
		'label' => array('text' => 'Постфикс цены', 'class' => 'control-label')
	));
	echo $this->PHForm->input('price_prefix2', array(
		'class' => 'input-small', 
		'label' => array('text' => 'Префикс цены RUR', 'class' => 'control-label')
	));
	echo $this->PHForm->input('price_postfix2', array(
		'class' => 'input-small', 
		'label' => array('text' => 'Постфикс цены RUR', 'class' => 'control-label')
	));
	echo $this->PHForm->input('int_div', array(
		'class' => 'input-small', 
		'label' => array('text' => 'Разделитель разрядов', 'class' => 'control-label')
	));
	echo $this->PHForm->input('xchg_rur', array(
		'class' => 'input-small', 
		'label' => array('text' => 'Курс RUR', 'class' => 'control-label')
	));

	echo $this->element('admin_content_end');
	echo $this->element('Form.btn_save');
	echo $this->PHForm->end();
?>
</div>