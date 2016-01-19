<?=$this->element('title', array('title' => $article2['Page']['title']))?>
<div class="block main">
	<div class="article">
		<?=$this->ArticleVars->body($article2)?>
	</div>
</div>
<a name="map"></a>
<?=$this->element('title', array('title' => $article['Page']['title']))?>
<div class="block main">
	<div class="article">
		<?=$this->ArticleVars->body($article)?>
	</div>
</div>
<?=$this->element('title', array('title' => 'Отправить сообщение'))?>
<form method="post" action="" id="postForm" class="feedback">
	<div class="block main">
		<p>
			Вы можете отправить нам сообщение.<br/>
			Поля, помеченные знаком <span class="star">*</span>, обязательны для заполнения.<br/>
		</p>
<?
	echo $this->Form->create('Contact');
	echo $this->Form->input('Contact.username', array('label' => array('text' => '<span class="star">*</span> Ваше имя')));
	echo $this->Form->input('Contact.email', array('label' => array('text' => '<span class="star">*</span> Ваш e-mail для обратной связи')));
	echo $this->Form->input('Contact.body', array('type' => 'textarea', 'label' => array('text' => '<span class="star">*</span> Ваше сообщение')));
	// echo $this->Form->input('captcha', array('type' => 'hidden', 'label' => array('text' => '<span class="star">*</span> '.__('Spam protection'))));
	echo $this->Form->label('captcha', '<span class="star">*</span> '.__('Spam protection'));
	echo $this->element('recaptcha');
	echo $this->Form->button(__('Send'), array('class' => 'submit', 'type' => 'submit'));
	echo $this->Form->end();
?>
	</div>
</form>
