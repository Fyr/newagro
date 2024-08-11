<?
    $phones = explode("\n", Configure::read('Settings.phone'));

    $url = 'tel:'.$this->ArticleVars->fixPhone(str_replace('+7', '8', $phones[0]));
    // array('class' => "callable ${type}")
?>
<div class="phones">
    <a href="<?=$url?>"><span class="icon phone"></span></a>
    <span class="numbers">
<?
    foreach($phones as $phone) {
?>
        <?=$this->ArticleVars->callableLink('tel', $phone)?><br />
<?
    }
?>
    </span>
</div>
<?
    $address = Configure::read('Settings.address');
    if ($address) {
?>
<div class="address clearfix">
    <a href="/contacts/#map" class="icon map"></a>
    <span class="text"><?=nl2br($address)?></span>
</div>
<?
    }
?>