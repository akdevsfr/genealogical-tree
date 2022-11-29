(function($){

	$(document.body).on('click', '.zqe-term-meta-image-field-wrapper button.zqe-term-meta-image-field-upload-button', function() {
		var button = this;
		event.preventDefault();
		event.stopPropagation();
		var frame;
		if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
			if (frame) {
				frame.open();
				return;
			}
			frame = wp.media.frames.select_image = wp.media({
				multiple: false
			});
			frame.on('open', function () {
				var selection = frame.state().get('selection');
				var current_attachment_id = $(button).prev().val();
				var attachment = wp.media.attachment(current_attachment_id);
				attachment.fetch();
				selection.add(attachment ? [attachment] : []);
			}); 
			frame.on('select', function () {
				var attachment = frame.state().get('selection').first().toJSON();
				if ($.trim(attachment.id) !== '') {
					var url = typeof attachment.sizes.thumbnail === 'undefined' ? attachment.sizes.full.url : attachment.sizes.thumbnail.url;
					$(button).prev().val(attachment.id);
					$(button).closest('.zqe-term-meta-image-field-wrapper').find('img').attr('src', url);
					$(button).next().show();
				}
			});
			frame.open();
		}
	});

	$(document.body).on('click', '.zqe-term-meta-image-field-wrapper button.zqe-term-meta-image-field-remove-button', function(){
		event.preventDefault();
		event.stopPropagation();
		var placeholder = $(this).closest('.zqe-term-meta-image-field-wrapper').find('img').data('placeholder');
		console.log(placeholder)
		$(this).closest('.zqe-term-meta-image-field-wrapper').find('img').attr('src', placeholder);
		$(this).prev().prev().val('');
		$(this).hide();
		return false;
	});

	$('.zqe-term-meta-color-picker').wpColorPicker()
	$('[data-dependency]').Dependency()

})(jQuery)