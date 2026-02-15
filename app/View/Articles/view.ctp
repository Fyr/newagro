<?
    if ($objectType === 'SiteArticle') {
        $this->Html->css(array('/Icons/css/icons'), array('inline' => false));
        $captchaKey = Configure::read('Recaptcha.publicKey');
        $this->Html->script(array(
            '/core/js/json_handler',
            'https://www.google.com/recaptcha/api.js?render='.$captchaKey
        ), array('inline' => false));
    }

	if ($objectType == 'SectionArticle') {

		if (isset($currSubcat)) {
			$breadcrumbs = array(
				__('Home') => '/',
				$category['SectionArticle']['title'] => SiteRouter::url($category),
				$article['SectionArticle']['title'] => ''
			);
		} else {
			$breadcrumbs = array(
				__('Home') => '/',
				$category['SectionArticle']['title'] => '',
			);
			//$this->ObjectType->getTitle('view', $objectType) => ''
		}
	} else {
		$route = array('controller' => 'Articles', 'action' => 'index', 'objectType' => $objectType);
		if (in_array($objectType, array('News', 'Offer')) && ($filial = Configure::read('domain.filial'))) {
			$route['filial'] = $filial;
		}
		$breadcrumbs = array(
			__('Home') => '/',
			$this->ObjectType->getTitle('index', $objectType) => $route,
			$this->ObjectType->getTitle('view', $objectType) => ''
		);
	}
	echo $this->element('bread_crumbs', array('aBreadCrumbs' => $breadcrumbs));
	echo $this->element('title', array('title' => $article[$objectType]['title']));
	if ($objectType == 'Dealer') {
		echo $this->element('/Article/view_Dealer', compact('article'));
	} else {
?>
<?
        if ($objectType === 'SiteArticle') {
?>
        <div class="views">
            <span class="">Просмотров: </span><?=$article[$objectType]['views']?>
            <span class="icon-color icon-preview"></span><br/>
            <span class="">Рейтинг: </span><?=$article[$objectType]['views']?>
        </div>
        <div class="time">
            <span class="icon clock" style="margin-right: 3px; position: relative; top: -1px; "></span><?=$this->PHTime->niceShort($article[$objectType]['created'])?>
        </div>
        <div class="author">
            <span class="">Автор: </span><?=$article[$objectType]['author']?>
        </div>

<?
        }
?>

<div class="block main clearfix">
	<div class="article">
		<?=$this->ArticleVars->body($article)?>
	</div>
</div>
<?
	}
	if ($objectType === 'SiteArticle') {
	    echo $this->Form->create('UserComment', array('class' => 'feedback register'));
?>
	<div class="block main">
<?
        if ($currUser) {
?>
        <p>
            <span class="star">*</span> Ваш комментарий появится после модерации.
        </p>
<?
            echo $this->Form->input('body', array('label' => array('text' => '<b>Оставить комментарий</b>')));
            echo $this->Form->hidden('UserComment.token');
            echo $this->Form->button(__('Submit'), array('type' => 'button', 'class' => 'submit', 'div' => false));
?>
<script>
$(function() {
	grecaptcha.ready(function() {
		$('#UserCommentForm .submit').click(function () {
			grecaptcha.execute('<?=$captchaKey?>', { action: 'register' }).then(function(token) {
				$('#UserCommentToken').val(token);
				$('#UserCommentForm').submit();
			});
		});
	});
});
</script>

<?
	    } else {
?>
	    <p>
	        Комментарии могут оставлять только авторизованные пользователи.<br/>
	        Войдите в <?=$this->Html->link('личный кабинет', array('controller' => 'user', 'action' => 'login'))?>, чтобы оставить комментарий. <br/>
	        Пройдите по этой <?=$this->Html->link('ссылке', array('controller' => 'pages', 'action' => 'action'))?> для регистрации.
	    </p>

<?
	    }
?>
	</div>
<?
	echo $this->Form->end();
	}
?>
