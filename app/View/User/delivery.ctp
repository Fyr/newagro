<?
	echo $this->element('title', array('title' => __('Delivery Address')));
?>
<div class="block main">
    <p>
        Введите адрес доставки.<br/>Данный адрес будет автоматически подставляться в ваш каждый заказ.
    </p>
<?
    echo $this->Form->create('User', array('class' => 'feedback'));
    echo $this->Form->input('delivery_address', array('label' => false));
    echo $this->Form->submit(__('Save'), array('class' => 'submit', 'div' => false));
    echo $this->Form->end();
?>
</div>
