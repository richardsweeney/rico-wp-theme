jQuery(function ($) {
	var History = window.History;
	if (!History.enabled) {
    return false;
	} else {
		History.Adapter.bind(window, 'statechange', function() {
			rpsPagination.statechange();
		});
	}
	var rpsPagination = {
		currentPage: '',
		start: {
      content: $('.posts-container').html(),
      url: window.location.href
    },
		paginationLinks: $('.blog-pagination a'),
		$postsContainer: $('.posts-container'),
		ajaxifyPagination: function(pageNo) {
			var that = this;
			that.paginationLinks.removeClass('current-page');
			that.$postsContainer.html('<span class="loading posts-loader" />');
			data = {
				action: 'rps-ajax-pagination',
				page: pageNo,
				offset: +ajaxGlobals.posts_per_page,
				cat: +ajaxGlobals.cat
			};
			$.ajax({
 				type: 'POST',
 				url: ajaxGlobals.ajaxurl,
 				cache: false,
 				data: data
 			}).done(function (response) {
				$('.loading').fadeOut();
 				$(that.paginationLinks[pageNo - 1]).addClass('current-page');
 				that.$postsContainer.html(response).hide().fadeIn(300);
 				History.pushState({page: pageNo}, '', '?page=' + pageNo);
			});
		},
		statechange: function() {
			var that = this,
					href = window.location.href,
					regex = /\?page=[0-9]/;
			if(regex.test(href) === true) {
  			var pageNo = +href.substr(href.length - 1);
  			if(that.currentPage !== pageNo) {
  				that.currentPage = pageNo;
  				that.ajaxifyPagination(pageNo);
  			}
			} else {
				if(href === that.start.url) {
					that.currentPage = 1;
					that.paginationLinks.removeClass('current-page');
 					$(that.paginationLinks[0]).addClass('current-page');
 					that.$postsContainer.html(that.start.content).hide().fadeIn(300);
 				}
			}
		}
	};
	if (History.enabled) {
		rpsPagination.paginationLinks.click(function (e) {
			var pageNo = +$(this).attr('data-page');
			rpsPagination.currentPage = pageNo;
			rpsPagination.ajaxifyPagination(pageNo);
			e.preventDefault();
		});
	}
});
