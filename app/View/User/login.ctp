<?
	echo $this->element('title', array('title' => __('User area login')))

	//echo $this->Form->input('User.username', array('class' => 'input-block-level', 'placeholder' => __('User name')));
	//echo $this->Form->input('User.password', array('class' => 'input-block-level', 'placeholder' => __('Password')));
?>
<form method="post" action="" id="postForm" class="feedback">
	<div class="block main">
		<p>
			<?=__('If you have no account, please %s', $this->Html->link(__('register yourself'), array('action' => 'register')))?>.
		</p>
<?
	$error = $this->Session->flash('auth');
	if ($error) {
?>
		<div class="error" style="margin-bottom: 20px;">!!!<?=$error?></div>
<?
	}

	// echo $this->Form->create('Contact');
	echo $this->Form->input('User.username', array('label' => array('text' => __('E-mail'))));
	echo $this->Form->input('User.password');
	echo $this->Form->hidden('Contact.token');
	// echo $this->Form->input('captcha', array('type' => 'hidden', 'label' => array('text' => '<span class="star">*</span> '.__('Spam protection'))));
	// echo $this->Form->label('captcha', '<span class="star">*</span> '.__('Spam protection'));
	// echo $this->element('recaptcha');
	echo $this->Form->button(__('Login'), array('class' => 'submit', 'type' => 'button'));
	// echo $this->Form->end();
?>
	</div>
</form>
<!--script>
$(function() {
	grecaptcha.ready(function() {
		$('#postForm .submit').click(function () {
			grecaptcha.execute('<?=$captchaKey?>', {action: 'contacts'}).then(function(token) {
				$('#ContactToken').val(token);
				$('#postForm').submit();
			});
		});
	});
});

</script-->
<?

?>