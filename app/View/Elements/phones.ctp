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
<?
    $address = Configure::read('Settings.address');
    if ($address) {
?>
<div class="address clearfix">
    <a href="/contacts/#map" class="icon map"></a>
    <span class="text"><?=$address?></span>
</div>
<?
    }
?>