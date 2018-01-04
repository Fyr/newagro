<?
	$this->Html->script(array('number_format', 'number_format'), array('inline' => false));
?>
<?=$this->element('admin_title', array('title' => __('Settings').': '.__('Prices')))?>
<div class="span8 offset2">
<?
	echo $this->PHForm->create('Settings');
	$aPricesForm = array(
		$this->PHForm->input('fk_price', array(
			'options' => $aPrices,
			'label' => array('text' => 'Показывать цену', 'class' => 'control-label')
		)),
		$this->PHForm->input('fk_price2', array(
			'options' => $aPrices,
			'label' => array('text' => 'Иначе ', 'class' => 'control-label')
		))
	);
	$aFormatForm = array(
		$this->PHForm->input('price_prefix', array(
			'class' => 'input-small',
			'label' => array('text' => 'Префикс цены', 'class' => 'control-label')
		)),
		$this->PHForm->input('int_div', array(
			'class' => 'input-small',
			'label' => array('text' => 'Разделитель разрядов', 'class' => 'control-label')
		)),
		$this->PHForm->input('float_div', array(
			'class' => 'input-small',
			'label' => array('text' => 'Дробная часть', 'class' => 'control-label')
		)),
		$this->PHForm->input('decimals', array(
			'class' => 'input-small',
			'label' => array('text' => 'Цифр в дробн.части', 'class' => 'control-label')
		)),
		$this->PHForm->input('price_postfix', array(
			'class' => 'input-small',
			// 'label' => array('text' => 'Постфикс цены <br/><small>($P для RUR)</small>', 'class' => 'control-label')
			'label' => array('text' => 'Постфикс цены <br/><small style="font-size: 0.8em">($P для ₽)</small>', 'class' => 'control-label')
		))
	);

	$aTabs = array(
		'Выбор цен' => implode('', $aPricesForm),
		'Формат' => implode('', $aFormatForm),
		'Брэнды' => $this->PHTableGrid->render('Brand', array(
			'actions' => array(
				'table' => array(),
				'row' => array(),
				'checked' => array()
			)
		))
	);
	echo $this->PHForm->hidden('brand_prices', array('value' => $this->request->data('brand_prices')));
	echo $this->element('admin_tabs', compact('aTabs'));
	echo $this->element('Form.btn_save');
	echo $this->PHForm->end();
?>
</div>
<script type="text/javascript">
$(document).ready(function(){
	var $grid = $('#grid_Brand');

	var vals = $('#SettingsBrandPrices').val().split(',');
	for(var i = 0; i < vals.length; i++) {
		$('.grid-chbx-row[value=' + vals[i] + ']', $grid).click();
	}

	$('.btn-primary').click(function(){
		var vals = [];
		$('.grid-chbx-row:checked', $grid).each(function(){
			vals.push($(this).val());
		});
		$('#SettingsBrandPrices').val(vals.join(','));
	});
});
</script>