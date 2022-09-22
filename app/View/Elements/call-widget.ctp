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
    foreach($aMessengers as $app) {
        $type = $app['Messenger']['type'];
        $title = $app['Messenger']['title'];
        $options = array('target' => '_blank', 'title' => $title, 'data-title' => $title, 'class' => 'widget-messengers-icon widget-messengers-icon--'.$type);
?>            
            <div class="widget-messengers-icon-wrap">
                <?=$this->ArticleVars->callableLink($type, $app['Messenger']['uid'], $options)?>
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