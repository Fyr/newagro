$(window).load(function() {
	$('.nivoSlider').nivoSlider({
		effect: 'fade',
		animSpeed: 250,
		pauseTime: 3000,
		directionNav: false,
		directionNavHide: false,
		controlNav: false,
		pauseOnHover: false
	});
});

var flag = true;
$(document).ready(function(){
	$('img[align="left"]').css('margin', '10px 10px 10px 0');
	$('img[align="right"]').css('margin', '10px 0px 10px 10px');

	$('#tags').css('left', -1000);
	
    $('.menu li a').click(function(){
        $(".header .menu li ul").stop().slideUp();
        if ( $(this).next().is('ul') ) {
            $(this).next('ul').stop().slideToggle();
        }
    });
    
	$(document).on('click touchstart', function(e) {
		if (!$.contains($(".header .menuDesktop").get(0), e.target)  ) {
			$(".header .menuDesktop li ul").stop().slideUp();
		}
        if (!$.contains($(".header .menuMobile").get(0), e.target)  ) {
			$(".header .menuMobile li ul").stop().slideUp();
		}
	});

	$('.catalog .firstLevel .icon.arrow').click(function(e){
		e.stopPropagation();
		$('.catalog .firstLevel').removeClass('active').parent().find('ul').hide();
		$(this).parent().toggleClass('active').parent().find('ul').slideToggle();
		return false;
	});

	if ($(window).width() <= 983 && $(window).width() > 703 ) {
		$(".rightSidebar").appendTo($(".oneLeftSide"));
	}
	flag = true;        
    $(window).resize(function() {
        if ($(window).width() <= 983 && $(window).width() > 703 ) {
            if (flag) {
			    $(".rightSidebar").appendTo($(".oneLeftSide"));
                flag = false;
            }
        } else {
			$(".rightSidebar").appendTo($(".mainColomn"));
            flag = true;
        }
    });
    
	$("#partnersParade").smoothDivScroll({ 
	    autoScrollingMode: "onStart", 
	    autoScrollingStep: 1, 
	    autoScrollingInterval: 20,
	    manualContinuousScrolling: true,
	    visibleHotSpotBackgrounds: "always",
	    hotSpotScrollingInterval: 30,
	    autoScrollingDirection: "endlessLoopRight",
	    mouseOverLeftHotSpot: function(eventObj, data) {
	        $(this).smoothDivScroll("option","autoScrollingDirection","endlessLoopLeft");
	    },
	    mouseOverRightHotSpot: function(eventObj, data) {
	        $(this).smoothDivScroll("option","autoScrollingDirection","endlessLoopRight");
	    }
	});
	
	// Logo parade event handlers
	$("#partnersParade").bind("mouseover", function() {
	    $(this).smoothDivScroll("stopAutoScrolling");
	}).bind("mouseout", function() {
	    $(this).smoothDivScroll("startAutoScrolling");
	});
	
	// auto fancy-box for images
	$('.block.main img').each(function(){
		$(this).wrap(function(){
			return '<a class="fancybox" href="' + this.src.replace(/\d+x\d*/g, 'noresize') + '" rel="photoalbum"></a>';
		});
	});
	if ($('.fancybox').length) {
		$('.fancybox').fancybox({
			padding: 5
		});
	}

	$('.ellipsis').dotdotdot();

	$('.article table > tbody > tr > td').each(function(){
		$(this).html($('p', this).html());
	});
});