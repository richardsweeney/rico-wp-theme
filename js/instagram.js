(function ($) {

  // adds .naturalWidth() and .naturalHeight() methods to jQuery
  // for retreaving a normalized naturalWidth and naturalHeight.
  var props = ['Width', 'Height'],
      prop;
  while (prop = props.pop()) {
    (function (natural, prop) {
      $.fn[natural] = (natural in new Image()) ?
      function () {
        return this[0][natural];
      } :
      function () {
        var
        node = this[0],
        img,
        value;

        if (node.tagName.toLowerCase() === 'img') {
          img = new Image();
          img.src = node.src,
          value = img[prop];
        }
        return value;
      };
    }('natural' + prop, prop.toLowerCase()));
  }

  $.fn.rpsLightbox = function(options) {

    return this.each(function () {

      var settings = $.extend({
        slideshow: false,
        slideshowSpeed: 3000
      }, options);

      var methods = {
        slideshowActive: false,
        $thisElement: $(this),
        $parentContainer: $(this).closest('ul'),
        numImages: $(this).closest('ul').children().length,
        init: function() {
          var $allImageLinks = methods.$thisElement.find('a');
          $.each($allImageLinks, function() {
            var img = new Image();
            img.src = this.href;
            $(img).load(function () {
              $(img).appendTo('body').hide();
            });
          });
        },
        open: function(link) {
          var docHeight = $(document).height(),
              $thisLink = link,
              title = ($thisLink.attr('title') != undefined) ? $thisLink.attr('title') : '',
              href = $thisLink.attr('href'),
              $container = $('<div class="rps-lightbox" />'),
              $content = $('<div class="rps-lightbox-content" />'),
              $image = $('<img class="lightbox-image hidden" src="' + href + '" title="' + title + '" />'),
              $close = $('<a class="close-trigger hidden" />'),
              $next = $('<a href="#" class="next hidden"> />'),
              $prev = $('<a href="#" class="previous hidden"> />'),
              $pause = $('<a href="#" class="pause hidden" />'),
              $win = $(window),
              winHeight = $win.height(),
              winWidth = $win.width(),
              scrollTop = $win.scrollTop(),
              left,
              top,
              contentHeight;

          methods.$thisElement = link;

          // I18n
          $close.text('close');
          $next.text('next');
          $prev.text('prev');
          $pause.text('pause');

          $container.append($content).height(docHeight);

          if (!$('body').hasClass('rps-lightbox-active')) {
            $('body').addClass('rps-lightbox-active').append($container);
            top = ((winHeight - 100) / 2) + scrollTop;
            $content.css({ width: 100, height: 100, 'margin-top': top });
          } else {
            $content = $('.rps-lightbox-content').empty();
          }

          $image.load(function () {
            var imgHeight = $image.naturalHeight(),
                imgWidth = $image.naturalWidth(),
                height,
                width,
                percentage;

            if (methods.slideshowActive === false) {
              methods.slideshowActive = true;
            }
            if (winHeight > imgHeight) {
              height = imgHeight;
              width = imgWidth;
            } else {
              height = Math.ceil(winHeight - (winHeight / 20)); // 5%
              percentage = imgHeight / winHeight;
              width = Math.ceil(imgWidth / percentage);
            }
            if (winWidth < imgWidth) {
              width = Math.ceil(winWidth - (winWidth / 20)); // 5%
              percentage = imgWidth / winWidth;
              height = Math.ceil(imgHeight / percentage);
            }
            heightNoPadding = height - 20;
            top = Math.ceil((winHeight - height) / 2) + scrollTop;

            if (settings.slideshow === true) {
              $content.append($pause);
            }
            if (methods.numImages > 1) {
              // more than one image, add the prev & next triggers
              $content.append($prev).append($next);
            }
            if (title !== '') {
              $content.append('<div class="title-container"><h1 class="image-title hidden">' + title + '</h1></div>');
            }
            $content.append($image).append($close).css({height: height, width: width, 'margin-top': top});

            window.setTimeout(function () {
              $image
                .removeClass('hidden')
                .one('click', methods.next)
                // Add touch support
                .one('touchy-swipe', function (event, target, data) {
                  if (data.direction === 'left') {
                    methods.previous();
                  } else if (data.direction === 'right') {
                    methods.next();
                  }
                });
              $('h1.image-title').removeClass('hidden');
              $('body')
                .one('keyup', function (e) {
                  if(e.keyCode === 27) {
                    methods.close();
                  } else if(e.keyCode === 37) {
                    methods.previous();
                  } else if(e.keyCode === 39) {
                    methods.next();
                  }
                })
                .one('click', methods.close);

              $close.one('click', methods.close).removeClass('hidden');
              $prev.one('click', methods.previous).removeClass('hidden');
              $next.one('click', methods.next).removeClass('hidden');
              $pause.one('click', methods.pause).removeClass('hidden');

              if (settings.slideshow === true) {
                methods.timeoutActive = window.setTimeout(methods.next, settings.slideshowSpeed);
              }

            }, 400);

          });
          return false;
        },
        close: function() {
          methods.slideshowActive = false;
          window.clearTimeout(methods.timeoutActive);
          delete methods.timeoutActive;
          if ($('.rps-lightbox-content').length) {
            $('.rps-lightbox-content').fadeOut(200, function () {
              $(this).remove();
            });
          }
          if ($('.rps-lightbox').length) {
            $('.rps-lightbox').remove();
          }
          $('body').off('keyup').removeClass('rps-lightbox-active');
        },
        next: function() {
          window.clearTimeout(methods.timeoutActive);
          var nextElement = methods.getElement('next');
          methods.open(nextElement);
          return false;
        },
        previous: function() {
          window.clearTimeout(methods.timeoutActive);
          var prevElement = methods.getElement('prev');
          methods.open(prevElement);
          return false;
        },
        pause: function() {
          window.clearTimeout(methods.timeoutActive);
          var nextElement = methods.getElement('next');
          $(this).addClass('paused').text('start').bind('click', function() {
            methods.open(nextElement);
            return false;
          });
          return false;
        },
        getElement: function(which) {
          var element;
          if (which === 'prev') {
            element = methods.$thisElement.parent().prev().find('a');
            if (!element.length) {
              element = methods.$parentContainer.find('a:last');
            }
          } else if (which === 'next') {
          element = methods.$thisElement.parent().next().find('a');
            if (!element.length) {
              element = methods.$parentContainer.find('a:first');
            }
          }
          return element;
        }
      };
      $('a', $(this)).bind('click', function (e) {
        methods.open($(this));
        e.preventDefault();
      });
      methods.init();

    });

  };

})(jQuery);

jQuery(function ($) {
  var instaJs = {
    settings: {
      url: 'https://api.instagram.com/v1/users/7332603/media/recent/',
      type: 'GET',
      dataType: 'jsonp',
      data: {
        access_token: '7332603.f59def8.c4998621b7684a87bf47628e0ddb4147',
        count: 12
      }
    },
    container: $('.instagram-container'),
    numResults: '',
    checkForResults: function() {
      var results,
          that = this,
          args = {
            hasStorage: true,
            savedResultsExist: true
          };
      if (Modernizr.localstorage === true) {
        if (localStorage.rsinstagram != undefined) {
          var cachedData = JSON.parse(localStorage.rsinstagram);
          if (cachedData.cacheExpires > ajaxGlobals.time.now) {
            that.createList(cachedData.data);
          } else {
            that.getFeed(args);
          }
        } else {
          args.savedResultsExist = false;
          that.getFeed(args);
        }
      } else {
        args.hasStorage = false;
        args.savedResultsExist = false;
        that.getFeed(args);
      }
    },
    getFeed: function(args) {
      var that = this;
    	$.ajax(this.settings).done(function(data) {
        var localObj = {
          data: data.data,
          cacheExpires: ajaxGlobals.time.oneHourFromNow
        };
        if (args.hasStorage === true) {
          localStorage.rsinstagram = JSON.stringify(localObj);
        }
        that.createList(localObj.data);
      }).fail(function(e) {
        that.container.append('failed to load feed.');
      });
    },
    createList: function(results) {
      var that = this,
          list = '<ul class="instagram-list clearfix">';
      that.numResults = results.length;
      $.each(results, function (i) {
        var result = results[i],
            thumb = result.images.thumbnail.url,
            large = result.images.standard_resolution.url,
            caption = (result.caption != null) ? result.caption.text : '',
            fullSize = $('<img class="full-size" src="' + large + '" />');
        list += '<li><a class="instagram-image" data-image-no="' + i + '" title="' + caption + '" href="' + large + '"><img class="lightbox-image" src="' + thumb + '" alt="instagram image"></a></li>';
        $('body').append(fullSize).find('img.full-size').hide();
      });
      list += '</ul>';
      $('.instagram-loading').remove();
      that.container.append(list);
      $('ul.instagram-list').rpsLightbox();
    }
  };
  instaJs.checkForResults();

});
