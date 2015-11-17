<!DOCTYPE html>
<html lang="ru">
<head>
	<title><?=$pageTitle?></title>
	<meta name="language" content="ru" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, user-scalable=no, maximum-scale=1.0, initial-scale=1.0, minimum-scale=1.0">
<?
	echo $this->Html->charset();
	echo $this->Html->meta('icon');
	echo $this->element('Seo.seo_info', array('data' => $seo));
	
	echo $this->Html->css(array('style', 'fonts', 'smoothDivScroll', 'extra', 'jquery.fancybox'));
?>
<!--[if gte IE 9]>
<style type="text/css">
    .gradient { filter: none; }
</style>
<![endif]-->
<?
	$scripts = array(
		'vendor/jquery/jquery-1.10.2.min', 
		'vendor/jquery/jquery-ui-1.10.3.custom.min', 
		'vendor/jquery/jquery.mousewheel.min', 
		'vendor/jquery/jquery.kinetic.min', 
		'vendor/jquery/jquery.smoothdivscroll-1.3-min',
		'vendor/jquery/jquery.nivo.slider.pack',
		'vendor/jquery/jquery.fancybox.pack',
		'doc_ready'
	);
	if ($disableCopy) {
		$scripts[] = 'nocopy';
	}
	echo $this->Html->script($scripts);
	
	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
?>
<?
	if (isset($cat_autoOpen)) {
?>
<script type="text/javascript">
$(document).ready(function(){
	$('#cat-nav<?=$cat_autoOpen?> > a').click();
});
</script>
<?
	}
?>
</head>
	<body>
		<div class="header">
            <div class="header_back">
                <div class="inner clearfix">
<?
	if ($isHomePage) {
?>
                   	<span class="logo"></span>
<?
	} else {
?>
					<a href="/" class="logo"></a>
<?
	}
?>
                   	<?=$this->element('main_menu')?>
                </div>
            </div>
            <div class="inner promoContent">
                <div class="right">
                    <div class="phones">
                        <span class="icon phone"></span>
                        <span class="numbers">
                            <?=Configure::read('Settings.phone')?><br />
                            <?=Configure::read('Settings.phone2')?>
                        </span>
                    </div>
                    <div class="address clearfix">
                        <a href="/contacts/#map" class="icon map"></a>
                        <span class="text"><?=Configure::read('Settings.address')?></span>
                    </div>
                </div>
                <div class="left">
                    <div class="skypeName">
                        <a href="callto:<?=Configure::read('Settings.skype')?>" class="icon skype"></a>
                        <a href="callto:<?=Configure::read('Settings.skype')?>"><?=Configure::read('Settings.skype')?></a>
                    </div>
                    <div class="letter">
                        <a href="mailto:<?=Configure::read('Settings.email')?>" class="icon email"></a>
                        <a href="mailto:<?=Configure::read('Settings.email')?>"><?=Configure::read('Settings.email')?></a>
                    </div>
                </div>
                <img src="/img/header.png" alt="" class="promoPicture" />
            </div>
            
        </div>
        <div class="wrapper clearfix">
            <form class="searchBlock" action="/products" method="get">
                <button class="submit">поиск</button>
                <div class="outerSearch"><input type="text" name="data[filter][Article.title]" placeholder="Введите номер или название запчасти..." /></div>
            </form>
            <div class="oneLeftSide">
                <div class="leftSidebar">
                    <?=$this->element('sidebar_left')?>
                </div>
            </div>

            <div class="mainColomn clearfix">
                <div id="mainContent" class="mainContent">
                    <div class="innerMainContent" <? if ($disableCopy) { ?>oncopy="return false;" onmousedown="return false;" onclick="return true;"<? } ?>>
<?
	if (isset($aSlot[1])) {
		foreach($aSlot[1] as $banner) {
			$min_w = 1980;
			echo $this->element('banner', compact('banner', 'min_w'));
		}
	}
	echo $this->element('bread_crumbs');
	echo $this->fetch('content');
	if (isset($aSlot[2])) {
		foreach($aSlot[2] as $banner) {
			$min_w = 1980;
			echo $this->element('banner', compact('banner', 'min_w'));
		}
	}
?>
                    </div>
                </div>
                <div class="rightSidebar">
                    <?=$this->element('sidebar_right')?>
                </div>
            </div>
        </div>
        <div class="wrapper">
<?
	if (isset($aHomePageNews)) {
?>

            <div class="headBlock">
                <div class="text">Новости нашей компании</div>
                <span class="corner"></span>
            </div>
            <div class="block clearfix">
<?
		foreach($aHomePageNews as $article) {
			$this->ArticleVars->init($article, $url, $title, $teaser, $src, '400x');
?>
                <div class="companyNews">
<?
			if ($src) {
?>
                    <img class="img-responsive" src="<?=$src?>" alt="<?=$title?>" />
<?
			}
?>
                    <div class="time"><span class="icon clock"></span><?=$this->PHTime->niceShort($article['Article']['created'])?></div>
                    <a href="<?=$url?>" class="title"><?=$title?></a>
                    <div class="description"><p><?=$teaser?></p></div>
                    <div class="more">
                        <?=$this->element('more', compact('url'))?>
                    </div>
                </div>
<?
		}
?>
            </div>
<?
	}
?>
            <div class="headBlock" style="margin-top: 14px">
                <div class="text">Наши партнеры</div>
                <span class="corner"></span>
            </div>
            
            <div class="block ourPartners">
                <div class="leftBack">
                    <div class="rightBack" id="partnersParade">
<?
	foreach($aBrandTypes as $article) {
		$this->ArticleVars->init($article, $url, $title, $teaser, $src, 'noresize');
?>
						<a href="<?=$url?>" target="_blank"><img src="<?=$src?>" alt="<?=$title?>" class="grayscale" /></a>
<?
	}
?>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer">
            <div class="wrapper clearfix">
                <div class="content clearfix">
                    <a href="/" class="logo"></a>
                    <?=$this->element('bottom_links')?>
                    <div class="footerAddress">
                        <div class="phones">
                            <span class="icon phone"></span>
                            <span class="numbers">
                                <?=Configure::read('Settings.phone')?><br />
                                <?=Configure::read('Settings.phone2')?>
                            </span>
                        </div>
                        <div class="address clearfix">
                        	<a href="/contacts/#map" class="icon map"></a>
                        	<span class="text"><?=Configure::read('Settings.address')?></span>
                        </div>
                    </div>
                    <div class="footerSkypeEmail">
	                    <div class="skypeName">
	                        <a href="callto:<?=Configure::read('Settings.skype')?>" class="icon skype"></a>
	                        <a href="callto:<?=Configure::read('Settings.skype')?>"><?=Configure::read('Settings.skype')?></a>
	                    </div>
	                    <div class="letter">
	                        <a href="mailto:<?=Configure::read('Settings.email')?>" class="icon email"></a>
	                        <a href="mailto:<?=Configure::read('Settings.email')?>"><?=Configure::read('Settings.email')?></a>
	                    </div>
                    </div>
                </div>
				<?=$this->element('counters')?>
                <img src="/img/footer_promo.png" class="footerPromo" alt="" />
            </div>
        </div>
        <div class="footerLine"></div>
<?
// 	if (!TEST_ENV) {
/*
 	<div style="position:fixed;top:50%;left:0px;">
<a id="mibew-agent-button" href="/app/webroot/mibew/chat?locale=ru" target="_blank" onclick="Mibew.Objects.ChatPopups['552c0eeae6c3cd3e'].open();return false;"><!--<img src="/app/webroot/mibew/b?i=mgreen&amp;lang=ru" border="0" alt="" />-->
<img src="/app/webroot/mibew/b?i=mgreen&amp;lang=ru" border="0" alt="" style="width: 38px; height: 160px;" />
</a><script type="text/javascript" src="/app/webroot/mibew/js/compiled/chat_popup.js"></script><script type="text/javascript">Mibew.ChatPopup.init({"id":"552c0eeae6c3cd3e","url":"\/app\/webroot\/mibew\/chat?locale=ru","preferIFrame":true,"modSecurity":false,"width":640,"height":480,"resizable":true,"styleLoader":"\/app\/webroot\/mibew\/chat\/style\/popup"});</script>
</div>
*/
	if (DOMAIN_NAME == 'agromotors.by') {
?>
<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
(function(){ var widget_id = 'nzJOgSwy2Q';
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);})();</script>
<!-- {/literal} END JIVOSITE CODE -->
<?
	} elseif (DOMAIN_NAME == 'agromotors.ru') {
?>
<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
(function(){ var widget_id = 'z8unW7ZOIo';
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);})();</script>
<!-- {/literal} END JIVOSITE CODE -->
<?
	}
?>
	</body>
</html>
