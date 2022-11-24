<?
    $skype = Configure::read('Settings.skype');
    if ($skype) {
?>
                    <div class="skypeName">
                        <a href="skype:<?=$skype?>" class="icon skype"></a>
                        <a href="skype:<?=$skype?>"><?=$skype?></a>
                    </div>
<?
    }
    $email = Configure::read('Settings.email');
    if ($email) {
?>
                    <div class="letter">
                        <a href="mailto:<?=$email?>" class="icon email"></a>
                        <a href="mailto:<?=$email?>"><?=$email?></a>
                    </div>
<?
    }
?>