<?
if ($aMessengers) {
?>
<div class="widget-messengers">
    <div class="widget-messengers-header"><?=Configure::read('domain.title')?></div>
    <div class="widget-messengers-container">
        <div class="widget-messengers-logo"></div>
        <div class="widget-messengers-heading"><?=__('Have a question? Call us!')?></div>
        <div class="widget-messengers-avatar"></div>
        <div class="widget-messengers-content">
<?
    $aLinks = array(
        'viber' => array('title' => 'Viber', 'url' => 'viber://chat?number='),
        'telegram' => array('title' => 'Telegram', 'url' => 'tg://resolve?domain='),
        'whatsapp' => array('title' => 'WhatsApp', 'url' => 'https://api.whatsapp.com/send?phone='),
        'skype' => array('title' => 'Skype', 'url' => 'callto:')
    );
    foreach($aMessengers as $app) {
        $type = $app['Messenger']['type'];
        $uid = $app['Messenger']['uid'];
        $url = $aLinks[$type]['url'];
        $title = $app['Messenger']['title'];
?>            
            <div class="widget-messengers-icon-wrap">
                <a target="_blank" href="<?=$url.$uid?>" title="<?=$title?>" data-title="<?=$title?>" class="widget-messengers-icon widget-messengers-icon--<?=$type?>"></a>
                <span><?=$title?></span>
            </div>
<?
    }
?>
            <div class="widget-messengers-icon-wrap">
                <a href="javascript:;" title="Закрыть" data-title="Закрыть" class="widget-messengers-icon widget-messengers-icon--close"></a>
                <span>&nbsp;</span>
            </div>
        </div>
        <div class="widget-messengers-description"></div>
    </div>
</div>
<?
}
?>