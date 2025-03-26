<?
    $this->Html->css(array('grid', '/Icons/css/icons'), array('inline' => false));
    $this->Html->script(array('cart', 'number_format', 'vendor/jquery/jquery.cookie'), array('inline' => false));

    // $aBreadCrumbs = array(__('Home') => '/', __('Cart') => '');
    // echo $this->element('bread_crumbs', compact('aBreadCrumbs'));
    echo $this->element('title', array('title' => __('Checkout')));

    if ($cartItems) {
        echo $this->element('cart_items');
        echo $this->element('title', array('title' => 'Ваши данные для заказа'));
?>
	<div class="block main feedback">
		<p>
			<?=__('Fields with %s are mandatory.', '<span class="star">*</span>')?><br/>
		</p>
		<?
		echo $this->Form->create('SiteOrder');
		echo $this->Form->input('SiteOrder.address', array('type' => 'textarea', 'label' => array('text' => '<span class="star">*</span> Адрес')));
		echo $this->Form->input('SiteOrder.comment', array('type' => 'textarea', 'label' => array('text' => 'Комментарий к заказу')));
		echo $this->Form->button('Заказать', array('class' => 'submit', 'type' => 'submit', 'onclick' => "console.log('submit'); this.disabled=true; $('#SiteOrderCartForm').submit()"));
		echo $this->Form->end();
		?>
	</div>
<?
    } else {
        echo $this->element('cart_empty');
    }
?>
