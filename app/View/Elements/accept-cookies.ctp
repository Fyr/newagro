        <div id="acceptCookie" style="display: none">
            <div style="display: inline-block; padding: 0 10px; max-width: 80%">
                <h1><?=$cookiesText['Page']['title']?></h1>
                <?=$this->ArticleVars->body($cookiesText)?>
                <div style="margin: 10px 0;">
                    <a class="accept" href="javascript:void(0)" onclick="AcceptCookies.update(1); $('#acceptCookie').hide()">Принять все cookies</a>
                    <button class="submit" type="text" onclick="$('#acceptCookie').hide()">Отказаться</button>
                </div>
            </div>
            <div class="widget-messengers-icon-wrap" style="float: right; width: 50px">
                <a href="javascript:;" title="Закрыть" data-title="Закрыть" class="widget-messengers-icon widget-messengers-cookie-close" onclick="$('#acceptCookie').hide()"></a>
                <span>&nbsp;</span>
            </div>
        </div>