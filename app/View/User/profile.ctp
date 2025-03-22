<?
    $userGroup = $this->request->data('User.group_id');

	echo $this->element('title', array('title' => __('Profile')));
?>
<div class="block main">
<?
    echo $this->Form->create('User', array('class' => 'feedback'));
    if ($userGroup == User::GROUP_COMPANY) {
        echo $this->element('user_company');
    } else {
        echo $this->element('user_profile');
    }
    echo $this->Form->submit(__('Save'), array('class' => 'submit', 'div' => false));
    echo $this->Form->end();
?>
</div>
