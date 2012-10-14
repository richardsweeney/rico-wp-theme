jQuery(function ($) {
	var twitter = {
		vars: {
			screen_name: 'richardsweeney',
      include_rts: true,
      count: 5,
      include_entities: true
		},
		link: function(tweet) {
    	return tweet.replace(/\b(((https*\:\/\/)|www\.)[^\"\']+?)(([!?,.\)]+)?(\s|$))/g, function (link) {
      	return '<a href="' + link + '">' + link + '</a>';
    	});
  	},
  	at: function(tweet) {
    	return tweet.replace(/\B[@＠]([a-zA-Z0-9_]{1,20})/g, function (m, username) {
      	return '<a href="http://twitter.com/intent/user?screen_name=' + username + '">@' + username + '</a>';
    	});
  	},
  	hash: function(tweet) {
    	return tweet.replace(/(^|\s+)#(\w+)/gi, function (m, before, hash) {
      	return before + '<a href="http://twitter.com/search?q=%23' + hash + '">#' + hash + '</a>';
    	});
  	},
		loadTweets: function() {
			var that = this;
			$.ajax({
        type: 'GET',
				url: 'http://api.twitter.com/1/statuses/user_timeline.json/',
        dataType: 'jsonp',
        data: that.vars,
        success: function(data, textStatus, xhr) {
        	var list = '<ol class="twitter-list clearfix">';
        	$.each(data, function (i) {
        		var tweet = that.link(that.at(that.hash(data[i].text)));
        		list += '<li>' + tweet + '</li>';
        	});
        	list += '</ol>';
        	$('.twitter-loading').remove();
        	$('h3.twitter-header').after(list);
        },
      });
		}
	};
	twitter.loadTweets();
});
