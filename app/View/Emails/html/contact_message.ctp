<?
	$email = h($this->request->data('Contact.email'));
?>
<b>Пользователь:</b> <?=h($this->request->data('Contact.username'))?>, <a href="mailto:<?=$email?>"><?=$email?></a> <br/>
<b>Текст сообщения:</b><br/>
<?=nl2br(h($this->request->data('Contact.body')))?>
