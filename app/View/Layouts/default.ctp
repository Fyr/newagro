<!DOCTYPE html>
<html lang="ru">
<head>
	<meta name="language" content="ru" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, user-scalable=no, maximum-scale=1.0, initial-scale=1.0, minimum-scale=1.0">
<?
	echo $this->Html->charset()."\n";
	echo $this->Html->meta('icon')."\n";
	echo $this->element('Seo.seo_info', array('data' => $seo))."\n";
	echo $this->Html->css(array('style.css?v=1', 'fonts', 'smoothDivScroll', 'extra.css?v=4', 'jquery.fancybox', 'call-widget'));
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
	if ($disableCopy) {
		$scripts[] = 'vendor/jquery/jquery.select.js';
	}
	$scripts = array_merge($scripts, array(
		'vendor/jquery/jquery.mousewheel.min',
		'vendor/jquery/jquery.kinetic.min',
		'vendor/jquery/jquery.smoothdivscroll-1.3-min',
		'vendor/jquery/jquery.nivo.slider.pack',
		'vendor/jquery/jquery.fancybox.pack',
		'vendor/jquery/jquery.dotdotdot',
		'doc_ready.js?v=2',
        'cart',
        'call-widget'
    ));
	echo $this->Html->script($scripts);
	
	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
?>
<script type="text/javascript">
var Cart;
$(document).ready(function(){
<?
    if (isset($cat_autoOpen)) {
?>
	$('#cat-nav<?=$cat_autoOpen?> > a').click();
<?
    }
?>
    Cart = new CartObject(".<?=Configure::read('domain.url')?>", "http://<?=Configure::read('domain.url').$this->Html->url(array('controller' => 'Products', 'action' => 'cart'))?>");
});
</script>
<!-- START: Counters for HEAD -->
<?
	if (isset($aSlot[7]) && !TEST_ENV) {
		foreach($aSlot[7] as $banner) {
			echo $banner['Banner']['options']['html']."\n";
		}
	}
?>

<!-- END: Counters for HEAD -->
</head>
	<body>
        <!-- Counters for BODY TOP: START -->
<?
	if (isset($aSlot[8]) && !TEST_ENV) {
		foreach($aSlot[8] as $banner) {
			echo $banner['Banner']['options']['html']."\n";
		}
	}
?>

        <!-- Counters for BODY TOP: END -->
		<div class="header">
            <div class="header_back">
                <div class="inner clearfix">
<?
    $homeURL = HTTP.Configure::read('domain.url');
?>
					<a href="<?=$homeURL?>" class="logo"></a>
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
                    <?=$this->element('sidebar_left')?>
                </div>
            </div>

            <div class="mainColomn clearfix">
                <div id="mainContent" class="mainContent">
                    <div class="innerMainContent" <? if ($disableCopy) { ?>oncopy="return false;" onmousedown="return false;" <? }?>>
<?
	if (isset($aSlot[1])) {
		foreach($aSlot[1] as $banner) {
			$min_w = 1980;
			echo $this->element('banner', compact('banner', 'min_w'));
		}
	}
	// echo $this->element('bread_crumbs');
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
                <div class="text"><?=__('News of our company')?></div>
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
                    <div class="time"><span class="icon clock"></span><?=$this->PHTime->niceShort($article['News']['created'])?></div>
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
                <a class="our-partners" href="<?=$this->Html->url(array('controller' => 'Articles', 'action' => 'index', 'objectType' => 'Brand'))?>">
                    <div class="text"><?=__('Our partners')?></div>
                </a>
                <span class="corner"></span>
            </div>
            
            <div class="block ourPartners">
                <div class="leftBack">
                    <div class="rightBack" id="partnersParade">
<?
	foreach($aBrands as $article) {
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
<?
	if (isset($aSlot[9]) && !TEST_ENV) {
		foreach($aSlot[9] as $banner) {
			echo $banner['Banner']['options']['html']."\n";
		}
	}
?>

                <!-- Counters for BODY BOTTOM: END -->
                <img src="/img/footer_promo.png" class="footerPromo" alt="" />
            </div>
        </div>
        <div class="footerLine"></div>

        <div class="mobileCatalogeBtn" id="mobile-cataloge-btn">
            <div class="text">Каталог</div>
        </div>

        <div class="mobileCataloge" id="mobile-cataloge">
            <div class="mm__bg mm__close"></div>
            <div class="mm__wrapper" id="mm__wrapper">
                <div class="close-btn mm__close"></div>
                <div class="mobileCataloge__inner">
                    <?=$this->element('sidebar_left')?> 
                </div>
            </div>
        </div>

<?
    // echo $this->element('sql_dump');
    echo $this->element('call-widget');
    echo $this->element('sql_stats');
?>
	</body>
</html>
