<?
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

<?
	echo $this->Form->submit(__('Register'), array('class' => 'submit', 'div' => false));
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
	<?/*
	grecaptcha.ready(function() {
		$('#postForm .submit').click(function () {
			grecaptcha.execute('<?=$captchaKey?>', {action: 'contacts'}).then(function(token) {
				$('#ContactToken').val(token);
				$('#postForm').submit();
			});
		});
	});
	*/ ?>
});

</script>
