<div class="social">
<?
    $aSocial = array(
        'vk' => 'https://vk.com/club114320957',
        'twitter' => 'https://twitter.com/AgromotorsRU',
        'fb' => 'https://www.facebook.com/profile.php?id=100011286312791',
        'youtube' => 'https://www.youtube.com/channel/UCsMo_bh47QjiJWDA6AmAs-A',
        'od' => 'https://www.ok.ru/group/54763448565770',
        'mailru' => 'https://my.mail.ru/mail/agromotorspeople/'
    );
    foreach($aSocial as $img => $url) {
?>
        <a href="<?=$url?>" target="_blank"><img src="/img/social/<?=$img?>.png" /></a>
<?
    }
?>
</div>