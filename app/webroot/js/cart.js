var CartObject = function(domain, url) {

	this.domain = domain;
	this.url = url;

	this.getData = function () {
		return JSON.parse($.cookie('cart') || '{}');
	};

	this.setData = function (data) {
		$.cookie('cart', JSON.stringify(data), {expires: 7, path: '/', domain: this.domain});
	};

	this.add = function (id) {
		var cart = this.getData();
		var lShowCart = !Object.keys(cart).length;
		var qty = parseInt($('.cart-qty').val());
		if (!qty) {
			return false;
		}

		cart[id] = qty;
		this.setData(cart);

		$('.add_cart').hide();
		$('.cart-qty').hide();
		$('.in-cart-qty').html(qty);
		$('.check_cart').show();

		if (lShowCart) {
			$('#cart').slideDown();
		}
		this.update();
	}

	this.update = function () {
		var cart = this.getData();
		$('.cart_items').html(Object.keys(cart).length);
	}

	this.checkout = function () {
		window.location.href = this.url;
	}

	this.updateTotal = function () {
		var total = 0;
		$('input[name="cart-qty"]').each(function(){
			total+= parseInt($(this).val());
		});
		$('#cart-total').html(total);
	}

	this.remove = function (id) {
		var cart = this.getData();
		delete cart[id];
		$('#cart-item_' + id).remove();
		this.setData(cart);
		this.update();
		this.updateTotal();
	}

	this.edit = function (id) {
		var cart = this.getData();
		var qty = parseInt($('#cart-item_' + id + ' input[name="cart-qty"]').val());
		if (!qty) {
			return false;
		}
		cart[id] = qty;
		this.setData(cart);
		this.updateTotal();
	}
}
