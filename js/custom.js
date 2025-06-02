(function ($) {

	"use strict";

	$(window).on('load', function () {

		$('#js-preloader').addClass('loaded');

	});

	$(window).on('load', function () {
		if ($(".wow").length) {
			var wow = new WOW({
				boxClass: 'wow',
				animateClass: 'animated',
				offset: 20,
				mobile: true,
				live: true,
			});
			wow.init();
		}
	});

	var width = $(window).width();
	$(window).resize(function () {
		if (width > 992 && $(window).width() < 992) {
			location.reload();
		} else if (width < 992 && $(window).width() > 992) {
			location.reload();
		}
	})

	if ($('.menu-trigger').length) {
		$(".menu-trigger").on('click', function () {
			$(this).toggleClass('active');
			$('.header-area .nav').slideToggle(200);
		});
	}

	$(window).on('load', function () {
		if ($('.cover').length) {
			$('.cover').parallax({
				imageSrc: $('.cover').data('image'),
				zIndex: '1'
			});
		}

		$("#preloader").animate({
			'opacity': '0'
		}, 600, function () {
			setTimeout(function () {
				$("#preloader").css("visibility", "hidden").fadeOut();
			}, 300);
		});
	});

})(window.jQuery);

$('input[type=file]').on('change', function () {
	let file = this.files[0];
	$(this).next().html(file.name);
});