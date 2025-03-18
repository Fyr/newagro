<?
    $userGroup = Hash::get($currUser, 'User.group_id');
    $userType = ($userGroup == User::GROUP_ADMIN) ? 'ADMIN' : $userGroups[$userGroup];
    $userName = ($userGroup == User::GROUP_COMPANY) ? Hash::get($currUser, 'UserCompany.company_name') : Hash::get($currUser, 'User.fio');
?>
<p>
    Вы вошли как <i><?=$userType?></i>:<br/>
    <b><?=$userName?></b>
</p>
<?
    echo $this->element('sbl_block', array('title' => __('User area'), 'content' => $this->element('user_menu')));
?>
