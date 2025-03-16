<!DOCTYPE html>
<html lang="ru">
<head>
	<meta name="language" content="ru" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, user-scalable=no, maximum-scale=1.0, initial-scale=1.0, minimum-scale=1.0">
<?
	echo $this->Html->charset()."\n";
	echo $this->Html->meta('icon')."\n";
	echo $this->Html->css(array('style.css?v='.$stylesVersion, 'fonts', 'smoothDivScroll', 'extra.css?v='.$stylesVersion, 'jquery.fancybox', 'call-widget'));
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
	);
    $scripts = array_merge($scripts, array(
		'vendor/jquery/jquery.mousewheel.min',
		'vendor/jquery/jquery.kinetic.min',
		'vendor/jquery/jquery.smoothdivscroll-1.3-min',
        'vendor/jquery/jquery.dotdotdot',
		'doc_ready.js?v=1',
        'call-widget'
    ));
	echo $this->Html->script($scripts);

	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
?>
<!-- START: Counters for HEAD -->
<!-- END: Counters for HEAD -->
</head>
	<body>
        <!-- Counters for BODY TOP: START -->
        <!-- Counters for BODY TOP: END -->
		<div class="header">
            <div class="header_back">
                <div class="inner clearfix">
                    <a href="/" class="logo"></a>
                   	<?=$this->element('main_menu')?>
                </div>
            </div>
            <div class="inner promoContent">
                <div class="right">
                    <?=$this->element('phones')?>
                </div>
                <div class="left">
                    <?=$this->element('contacts')?>
                </div>
                <img src="/img/header.png" alt="" class="promoPicture" />
            </div>

        </div>
        <div class="wrapper clearfix">
            <form class="searchBlock" action="/products" method="get">
                <button class="submit"><?=__('search')?></button>
                <div class="outerSearch"><input type="text" name="q" value="<?=$this->request->query('q')?>" placeholder="<?=__('Enter spare number or its name...')?>" /></div>
            </form>
            <div class="oneLeftSide">
                <div class="leftSidebar">
                    <?//$this->element('sidebar_left')?>
                </div>
            </div>

            <div class="mainColomn clearfix">
                <div id="mainContent" class="mainContent">
                    <div class="innerMainContent">
<?
	echo $this->fetch('content');
?>
                    </div>
                </div>
                <div class="rightSidebar">
                    <?//$this->element('sidebar_right')?>
                </div>
            </div>
        </div>
        <div class="wrapper">
        </div>
        <div class="footer">
            <div class="wrapper clearfix">
                <div class="content clearfix">
                    <a href="/" class="logo"></a>
                    <?=$this->element('bottom_links')?>
                    <div class="footerAddress">
                        <?=$this->element('phones')?>
                    </div>
                    <div class="footerSkypeEmail">
<?
    echo $this->element('contacts');
    if (Configure::read('domain.zone') == 'ru' || TEST_ENV) {
        echo $this->element('social');
    }
?>
                    </div>
                </div>
                <!-- Counters for BODY BOTTOM: START -->
                <!-- Counters for BODY BOTTOM: END -->
                <img src="/img/footer_promo.png" class="footerPromo" alt="" />
            </div>
        </div>
        <div class="footerLine"></div>
<?
    // echo $this->element('sql_dump');
    echo $this->element('call-widget');
    // echo $this->element('sql_stats');
?>
	</body>
</html>
