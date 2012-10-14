jQuery(function ($) {

	var RPS = {
		errors: {
			name: false,
			email: false,
			message: false
		},
		message: [],
		parent: $('.rps-contact-form'),
		submit: $('.rps-contact-form input.go'),
		loader: $('<span class="loading" />'),
		id: $('rps-contact-form').attr('data-id'),
		form: {
			name: $('.rps-contact-form input[name="contact-name"]'),
			email: $('.rps-contact-form input[name="contact-email"]'),
			message: $('.rps-contact-form textarea[name="contact-message"]')
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
				case 'contact-name':
					if (val === '') {
						this.errors.name = error = true;
						message = 'Don\'t forget your name.';
					} else {
						this.errors.name = error = false;
					}
					break;
				case 'contact-email':
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
				case 'contact-message':
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
					honeyPot = $.trim(that.parent.find('input[name="contact-url"]').val()),
					feedback = $('<span class="message-feedback" />'),
					message;

			this.loader.appendTo(this.parent);
			if($('span.message-feedback').length) {
				$('span.message-feedback').remove();
			}
			$.each(this.form, function () {
				that.validateForm($(this));
			});

			if (honeyPot !== '') {
				message = 'Sorry, your message has been flagged as spam. Please try to send your message again.';
				that.loader.remove();
				feedback.text(message).appendTo(that.parent).hide().fadeIn(200);
			} else {
				if (this.errors.name === false && this.errors.email === false && this.errors.message === false) {
		 			$.ajax({
		 				type: 'POST',
		 				url: ajaxGlobals.ajaxurl,
		 				data: {
							action : 'contact-form-ajax',
							form: {
								id: that.id,
								name: that.form.name.val(),
								email: that.form.email.val(),
								message: that.form.message.val()
							}
						}
		 			}).done(function (response) {
		 				if (response.spam === 'no') {
		 					if (response.mail_sent === 'yes') {
			 					message = 'Thanks, your message has been sent!';
				 				$.each(that.form, function () {
									$(this).val('');
								});
				 			} else if (response.mail_sent === 'no') {
				 				message = 'Darn, your message couldn\'t be sent :( Very sorry about that. Please try again';
				 			}
			 			} else if (response.spam === 'yes') {
			 				message = 'Sorry, your message has been flagged as spam. Please try to send your message again.';
			 			}
			 			that.loader.remove();
			 			feedback.text(message).appendTo(that.parent).hide().fadeIn(200);
					});
		 		}
		 		if (this.errors.name === true || this.errors.email === true || this.errors.message === true) {
		 			this.loader.remove();
		 			var offset = that.parent.offset(),
		 					top = offset.top - 20;
		 			console.log(offset);
		 			$('html, body').animate({
		 				scrollTop: top
		 			}, 400);
		 		}
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