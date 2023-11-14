<?
    $phone = Configure::read('Settings.phone1');
    $phone2 = Configure::read('Settings.phone2');

    $url = 'tel:'.$this->ArticleVars->fixPhone(str_replace('+7', '8', Configure::read('Settings.phone1')));
    // array('class' => "callable ${type}")
?>
<div class="phones">
    <a href="<?=$url?>"><span class="icon phone"></span></a>
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