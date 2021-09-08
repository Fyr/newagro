<?php

$wm_heading = __('Have a question? Call us!');
$wm_viber = '+237489203458273';
$wm_telegram = '@asiudfhapsid';
$wm_whatsapp = '+w934879wfaasdkfapd';
$messenger_skype = 'ldsakjfls';

$messenger_viber = $wm_viber;
$messenger_telegram = $wm_telegram;
$messenger_whatsapp = $wm_whatsapp; 

$aLinks = array(
    'Viber' => 'viber://chat?number=',
    'Telegram' => 'tg://resolve?domain=',
    'WhatsApp' => 'https://api.whatsapp.com/send?phone=',
    'Skype' => 'callto:'
);
?>
<div class="widget-messengers">
    <div class="widget-messengers-container">
        <div class="widget-messengers-heading"><? echo $wm_heading; ?></div>
        <div class="widget-messengers-avatar"></div>
        <div class="widget-messengers-content">
<?
foreach($aLinks as $title => $url) {
    $type = strtolower($title);
    $uid = Configure::read('Settings.'.$type);
    if ($uid) {
?>            
            <a target="_blank" href="<?=$url.$uid?>" data-title="<?=$title?>" class="widget-messengers-icon widget-messengers-icon--<?=$type?>"></a>
<?
    }
}
?>
            <a href="javascript:;" data-title="Закрыть" class="widget-messengers-icon widget-messengers-icon--close"></a>
        </div>
        <div class="widget-messengers-description"></div>
    </div>
</div>