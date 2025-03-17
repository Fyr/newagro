<?
	echo $this->element('title', array('title' => __('User area login')))
?>
<form method="post" action="" id="postForm" class="feedback">
	<div class="block main">
<?
	$error = $this->Session->flash('auth');
	if ($error) {
?>
		<div class="error-message" style="margin-bottom: 20px;"><?=$error?></div>
<?
	}

	echo $this->Form->input('User.username', array('label' => array('text' => __('E-mail'))));
	echo $this->Form->input('User.password');
	echo $this->Form->button(__('Login'), array('class' => 'submit', 'type' => 'submit'));
?>
	</div>
	<p>
        <?=__('If you have no account, please %s', $this->Html->link(__('register yourself'), array('controller' => 'pages', 'action' => 'register')))?>.
    </p>
</form>
