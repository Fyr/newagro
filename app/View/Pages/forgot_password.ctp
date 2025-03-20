<?
	echo $this->element('title', array('title' => __('Forgot password')));
	echo $this->Form->create('PasswordRecover', array('class' => 'feedback register', 'url' => '#recover'));
?>
    <a name="recover"></a>
	<div class="block main">
		<p>
			Введите e-mail вашей учетной записи для восстановления пароля
		</p>

<?
	echo $this->Form->input('PasswordRecover.email'); // , array('label' => false)
	echo $this->Form->submit(__('Next'), array('class' => 'submit', 'div' => false));
	//
?>
	</div>
<?
	echo $this->Form->end();
?>
