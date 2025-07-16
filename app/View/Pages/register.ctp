<?
    $captchaKey = Configure::read('Recaptcha.publicKey');
    $this->Html->script(array(
        '/core/js/json_handler',
        'https://www.google.com/recaptcha/api.js?render='.$captchaKey
    ), array('inline' => false));

	echo $this->element('title', array('title' => __('Registration')));
	$selected = $this->request->data('User.group_id');
	if (!$selected) {
	    $selected = User::GROUP_USER;
	}
	echo $this->Form->create('User', array('class' => 'feedback register'));
?>
	<div class="block main">
		<p>
			<?=__('Fields with %s are mandatory.', '<span class="star">*</span>')?>
			<div class="error-message"><?=$recaptchaError?></div>
		</p>
		<div class="radio">
			<?=__('Choose your account type')?>:<br/>
<?
	echo $this->Form->radio('group_id', $accountTypeOptions, array('legend' => false, 'value' => $selected, 'onclick' => 'onChooseAccountType(this.value)'));
?>
		</div>
		<fieldset>
			<legend>Учетная запись</legend>
<?
    echo $this->Form->input('User.email', array('label' => array('text' => '<span class="star">*</span> '.__('E-mail'))));
	echo $this->Form->input('User.password', array('label' => array('text' => '<span class="star">*</span> '.__('Password'))));
	echo $this->Form->input('User.password_confirm', array('type' => 'password', 'label' => array('text' => '<span class="star">*</span> '.__('Password Confirm'))));
?>
		</fieldset>
		<fieldset class="registerType" id="registerType<?=User::GROUP_USER?>">
			<legend>Профиль: <?=$accountTypeOptions[User::GROUP_USER]?></legend>
            <?=$this->element('user_profile')?>
		</fieldset>
		<fieldset class="registerType" id="registerType<?=User::GROUP_COMPANY?>">
			<legend>Профиль: <?=$accountTypeOptions[User::GROUP_COMPANY]?></legend>
            <?=$this->element('user_company')?>
		</fieldset>
		<fieldset>
            <legend>Адрес доставки для заказов</legend>
            <p>
                Данный адрес будет автоматически подставляться в ваш каждый заказ<br/>
                Также вы сможете изменить его позже из Личного кабинета
            </p>
            <?=$this->Form->input('User.delivery_address', array('label' => false))?>
        </fieldset>

<?
    echo $this->Form->hidden('User.token');
	echo $this->Form->button(__('Register'), array('type' => 'button', 'class' => 'submit', 'div' => false));
	//
?>
	</div>
<?
	echo $this->Form->end();
?>
<script>
function onChooseAccountType(accType) {
	$('.registerType').hide();
	$('.registerType input').attr('required', false);
	$('#registerType' + accType).show();
	$('#registerType' + accType + ' input').attr('required', true);
}

$(function() {
	onChooseAccountType(<?=$selected?>);
	grecaptcha.ready(function() {
		$('#UserRegisterForm .submit').click(function () {
			grecaptcha.execute('<?=$captchaKey?>', { action: 'register' }).then(function(token) {
				$('#UserToken').val(token);
				$('#UserRegisterForm').submit();
			});
		});
	});
});

</script>
