$(document).ready(function(){
    $('.widget-messengers-icon--close').click(function () {
        $('.widget-messengers').hide();
        time = 1000*60*60*24*1; // 1 day
        var expires = new Date((new Date()).valueOf() + time);
        document.cookie = "hidemewidgetmessengers=true;expires=" + expires.toUTCString();          
    });
    
    if (document.cookie.indexOf('hidemewidgetmessengers=true') == -1 || true) {
        $('.widget-messengers').css('display','block');
        $('.widget-messengers').css('transition','right 500ms');
        $('.widget-messengers').css('right','30px');
    }
});