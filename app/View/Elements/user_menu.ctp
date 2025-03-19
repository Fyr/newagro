<?
    $active = 0;
    $style = ($active) ? '' : 'style="display: none"';
    $aUserMenu = array(
        'profile' => array('title' => __('Profile'), 'action' => 'profile'),
        'delivery' => array('title' => __('Delivery address'), 'action' => 'delivery'),
        'checkout' => array('title' => __('Checkout'), 'action' => 'checkout'),
        'orders' => array('title' => __('My orders'), 'action' => 'orders'),
        'changePassword' => array('title' => __('Change password'), 'action' => 'changePassword'),
        'logout' => array('title' => __('Log out'), 'action' => 'logout'),
    );
?>

<ul class="catalog">
<?
    foreach($aUserMenu as $slug => $menu) {
        $url = $this->Html->url(array('controller' => 'user', 'action' => $menu['action']));
?>
    <li>
        <a href="<?=$url?>" class="firstLevel"><span class="icon arrow"></span><?=$menu['title']?></a>
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
