<?
    $phone = Configure::read('Settings.phone1');
    $phone2 = Configure::read('Settings.phone2');
?>
<div class="phones">
    <span class="icon phone"></span>
    <span class="numbers">
        <?=$this->ArticleVars->callableLink('tel', Configure::read('Settings.phone1'))?><br />
        <?=$this->ArticleVars->callableLink('tel', Configure::read('Settings.phone2'))?>
    </span>
</div>