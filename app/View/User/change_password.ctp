<?
	echo $this->element('title', array('title' => __('Change Password')));
?>
<div class="block main">
<?
    echo $this->Form->create('User', array('class' => 'feedback'));
    echo $this->Form->input('User.password', array('label' => array('text' => __('New password'))));
    echo $this->Form->input('User.password_confirm', array('type' => 'password', 'label' => array('text' => __('Password Confirm'))));
    echo $this->Form->submit(__('Save'), array('class' => 'submit', 'div' => false));
    echo $this->Form->end();
?>
</div>
