jQuery(function($) {
	// bind event handlers to modal triggers
	$('body').on('click', '.trigger', function(e) {
		e.preventDefault();
		$('#test-modal').modal().open();
	});

	// attach modal close handler
	$('.modal .close').on('click', function(e) {
		e.preventDefault();
		$.modal().close();
	});

	$(".trigger").click(function() {
		var windowWidth = $('html').width();

		var windowWidth = $('html').width();
		var windowHeight = $('html').height();

		var imageWidth = $('.src-image').actual('width');
		var imageHeight = $('.src-image').actual('height');

		var maxImageWidth = windowWidth - 300;
		var maxImageHeight = windowHeight - 150;

		var modalWidth = imageWidth + 70;
		var modalHeight = imageHeight + 87;

		/*
		 * $('.maxImageWidth').text("maxImageWidth: " + maxImageWidth);
		 * $('.maxImageHeight').text("maxImageHeight: " + maxImageHeight);
		 * $('.modalWidth').text("modalWidth: " + modalWidth);
		 * $('.modalHeight').text("modalHeight: " + modalHeight);
		 */

		if (modalWidth > maxImageWidth) {
			$(".modal").css('width', maxImageWidth + "px");
		} else {
			$(".modal").css('width', modalWidth + "px");
		}

		if (modalHeight > maxImageHeight) {
			$(".modal").css('height', maxImageHeight + "px");
		} else {
			$(".modal").css('height', modalHeight + "px");
		}
	});
});