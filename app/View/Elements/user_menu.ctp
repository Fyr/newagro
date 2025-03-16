<?
    $active = 0;
    $style = ($active) ? '' : 'style="display: none"';
?>
<ul class="catalog">
    <li>
        <a href="javascript:void(0)" class="firstLevel"><span class="icon arrow"></span><?=__('Profile')?></a>
        <ul <?=$style?>>
            <li><a <?=$active?> href="/profile"><span class="icon smallArrow"></span><?=__('Change profile')?></a></li>
        </ul>            
    </li>
</ul>
