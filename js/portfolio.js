jQuery(function ($) {
	var $tagline = $('.tagline'),
			height = '50px';
	$tagline.css({ 'height': 0, 'padding': 0, 'opacity': 0 });
	$('.image-container').hover(function () {
		$(this)
			.find('.tagline')
				.css({ 'padding': '1.5% 3%' })
				.stop(true, true)
				.animate({ 'height': height, 'opacity': 1 }, 400);
	}, function () {
		$(this)
			.find('.tagline')
				.css({ 'padding': 0 })
				.stop(true, true)
				.animate({ 'height': 0, 'opacity': 0 }, 150);
	});
});
