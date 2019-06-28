<?
	/*
	$captchaKey = Configure::read('RecaptchaV3.publicKey');
	$this->Html->script(array(
		'/core/js/json_handler',
		'https://www.google.com/recaptcha/api.js?render='.$captchaKey
	), array('inline' => false));
	*/
?>
<?=$this->element('title', array('title' => $article2['Page']['title']))?>
<div class="block main">
	<div class="article">
		<?=$this->ArticleVars->body($article2)?>
	</div>
</div>
<a name="map"></a>
<?=$this->element('title', array('title' => $article['Page']['title']))?>
<div class="block main">
	<div class="article">
		<?=$this->ArticleVars->body($article)?>
	</div>
</div>
<?
/*
	echo $this->element('title', array('title' => __('Send message')))
?>
<form method="post" action="" id="postForm" class="feedback">
	<div class="block main">
		<p>
			<?=__('You can send a message for us.')?><br/>
			<?=__('Fields with %s are mandatory.', '<span class="star">*</span>')?><br/>
		</p>

	<div class="error-message" style="margin-bottom: 20px;"><?=$recaptchaError?></div>
<?
	echo $this->Form->create('Contact');
	echo $this->Form->input('Contact.username', array('label' => array('text' => '<span class="star">*</span> '.__('Your name'))));
	echo $this->Form->input('Contact.email', array('label' => array('text' => '<span class="star">*</span>'.__('Your e-mail for reply'))));
	echo $this->Form->input('Contact.body', array('type' => 'textarea', 'label' => array('text' => '<span class="star">*</span> '.__('Your message'))));
	echo $this->Form->hidden('Contact.token');
	// echo $this->Form->input('captcha', array('type' => 'hidden', 'label' => array('text' => '<span class="star">*</span> '.__('Spam protection'))));
	// echo $this->Form->label('captcha', '<span class="star">*</span> '.__('Spam protection'));
	// echo $this->element('recaptcha');
	echo $this->Form->button(__('Send'), array('class' => 'submit', 'type' => 'button'));
	// echo $this->Form->end();
?>
	</div>
</form>
<script>
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

</script>
<?
*/
?>