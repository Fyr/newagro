<?
    // $style = ($active) ? '' : 'style="display: none"';
    $aUserMenu = array(
        'profile' => array('title' => __('Profile'), 'action' => 'profile'),
        'delivery' => array('title' => __('Delivery Address'), 'action' => 'delivery'),
        'cart' => array('title' => __('Checkout'), 'action' => 'cart'),
        'orders' => array('title' => __('My orders'), 'action' => 'orders'),
        'changePassword' => array('title' => __('Change Password'), 'action' => 'changePassword'),
        'logout' => array('title' => __('Log out'), 'action' => 'logout'),
    );
?>

<ul class="catalog usermenu">
<?
    foreach($aUserMenu as $slug => $menu) {
        $url = $this->Html->url(array('controller' => 'user', 'action' => $menu['action']));

        $active = ($slug == $this->request->action) ? 'active' : '';
        $title = ($active) ? $menu['title'] : '<span class="icon arrow"></span>'.$menu['title'];

?>
    <li>
        <a href="<?=$url?>" class="firstLevel <?=$active?>"><?=$title?></a>
    </li>
<?
    }
?>
</ul>
<? /*

    <li>
        <a href="javascript:void(0)" class="firstLevel"><span class="icon arrow"></span><?=__('Profile')?></a>
        <ul style="display: none">
            <li><a <?=$active?> href="/profile"><span class="icon smallArrow"></span><?=__('Change profile')?></a></li>
        </ul>
    </li>
*/?>
