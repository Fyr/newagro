var AcceptCookiesObject = function(domain, url) {
	this.domain = domain;

	this.getData = function () {
		return JSON.parse($.cookie('accept-cookies') || '{}');
	};

	this.setData = function (data) {
		$.cookie('accept-cookies', JSON.stringify(data), { expires: 365, path: '/', domain: this.domain });
	};

    this.update = function(isAccepted) {
        if (isAccepted !== undefined) {
            this.setData({ isAccepted: true });
        }
        var data = this.getData();
        if (!(data && data.isAccepted)) {
            $('#acceptCookie').show();
        }
    }
}
