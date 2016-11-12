<div class="social">
<?
    $aSocial = array(
        'vk' => 'https://vk.com/club114320957',
        'twitter' => 'https://twitter.com/AgromotorsRU',
        'fb' => 'https://www.facebook.com/profile.php?id=100011286312791',
        'youtube' => 'https://www.youtube.com/channel/UCsMo_bh47QjiJWDA6AmAs-A'
    );
    foreach($aSocial as $img => $url) {
?>
        <a href="<?=$url?>" target="_blank"><img src="/img/social/<?=$img?>.png" /></a>
<?
    }
?>
</div>