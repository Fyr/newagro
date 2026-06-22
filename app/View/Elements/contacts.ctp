<?
    $skype = Configure::read('Settings.skype');
    if ($skype) {
?>
                    <div class="skypeName">
                        <a rel="nofollow" href="skype:<?=$skype?>" class="icon skype"></a>
                        <a rel="nofollow" href="skype:<?=$skype?>"><?=$skype?></a>
                    </div>
<?
    }
    $email = Configure::read('Settings.email');
    if ($email) {
?>
                    <div class="letter">
                        <a rel="nofollow" href="mailto:<?=$email?>" class="icon email"></a>
                        <a rel="nofollow" href="mailto:<?=$email?>"><?=$email?></a>
                    </div>
<?
    }
?>
