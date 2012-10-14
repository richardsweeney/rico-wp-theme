jQuery(function ($) {

	var RPS = {
		errors: {
			name: false,
			email: false,
			message: false
		},
		message: [],
		parent: $('#comment-form'),
		submit: $('#submit'),
		loader: $('<span class="loading" />'),
		form: {
			name: $('#comment-form #author'),
			email: $('#comment-form #email'),
			message: $('#comment-form #comment')
		},
		focusForm: function(input) {
			var type = input.attr('name'),
					$span = $('span.error-message.' + type);
			input.removeClass('error');
			if($span.length) {
				$span.fadeOut(200, function() {
					$(this).remove();
				});
			}
		},
		validateForm: function(input) {
			var type = input.attr('name'),
					val = $.trim(input.val()),
					messageContainer = $('<span class="error-message" />'),
					message,
					error = false,
					emailRegEx = /\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i;

			messageContainer.addClass(type);

			var errorMessage = input.parent().find('.error-message');
			if (errorMessage.length) {
				errorMessage.remove();
			}

			switch(type) {
				case 'author':
					if (val === '') {
						this.errors.name = error = true;
						message = 'Don\'t forget your name.';
					} else {
						this.errors.name = error = false;
					}
					break;
				case 'email':
					if (val === '') {
						this.errors.email = error = true;
						message = 'Don\'t forget your email address.';
					} else {
						this.errors.email = error = false;
						if (emailRegEx.test(val) === false) {
							this.errors.email = error = true;
							message = 'This email address seems invalid';
						} else {
							this.errors.email = error = false;
						}
					}
					break;
				case 'comment':
					if (val === '') {
						this.errors.message = error = true;
						message = 'Don\'t forget to leave a message!';
					} else {
						this.errors.message = error = false;
					}
					break;
			}

			if(error === true) {
				input.addClass('error');
				messageContainer.text(message).insertAfter(input).hide().fadeIn(200);
			}

		},
		ajaxSubmit: function() {
			var that = this,
					feedback = $('<span class="message-feedback" />'),
					message;

			this.loader.appendTo(this.parent);
			if($('span.message-feedback').length) {
				$('span.message-feedback').remove();
			}
			$.each(this.form, function () {
				that.validateForm($(this));
			});

			if (this.errors.name === false && this.errors.email === false && this.errors.message === false) {
	 			$.ajax({
	 				type: 'POST',
	 				url: ajaxGlobals.ajaxurl,
	 				data: {
						action : 'rps-comments-ajax-submit',
						form: {
							id: +$('#comment_post_ID').val(),
							name: that.form.name.val(),
							email: that.form.email.val(),
							message: that.form.message.val()
						}
					}
	 			}).done(function (response) {
	 				console.log(response);
	 				if (response.spam === 'no') {
	 					if (response.comment_sent === 'yes') {
	 						if ($('ol.commentlist').length) {
		 						$('ol.commentlist').append(response.comment).find('li').last().hide().fadeIn(300);
		 					} else {
		 						var $ol = $('<ol class="commentlist" />');
		 						$ol.html(response.comment).prependTo('.comments-container').hide().fadeIn(300);
		 					}
			 				$.each(that.form, function () {
								$(this).val('');
							});
			 			} else if (response.comment_sent === 'no') {
			 				message = 'Darn, your comment couldn\'t be sent :( Very sorry about that. Please try again';
			 				feedback.text(message).appendTo(that.parent).hide().fadeIn(200);
			 			}
		 			} else if (response.spam === 'yes') {
		 				message = 'Sorry, your comment has been flagged as spam. Please try to send your message again.';
		 				feedback.text(message).appendTo(that.parent).hide().fadeIn(200);
		 			}
		 			that.loader.remove();
				});
	 		}
	 		if (this.errors.name === true || this.errors.email === true || this.errors.message === true) {
	 			this.loader.remove();
	 		}
	 	}
	};

	if(Modernizr.input.placeholder === true) {
		RPS.parent.find('label').hide();
	}

	$.each(RPS.form, function () {
		$(this).focus(function () {
			RPS.focusForm($(this));
		})
		$(this).blur(function () {
			RPS.validateForm($(this));
		});
	});

	RPS.submit.click(function () {
		RPS.ajaxSubmit();
		return false;
	});

});