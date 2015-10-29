/**
 * Global Rats Records namespace
 * @required jQuery
 */
var RR = {
    $container: $('#rr-container')
};

/**
 * Full content loader
 * @constructor
 */
RR.contentLoader = function(url) {
    this.url = url;
    this.$loading = $('.rr-loading-stage');
    this.$loadingMessage = this.$loading.find('.rr-loading-message');
    this.messagesInterval = null;
    this.messagesIntervalTime = 400;
    this.messagesIntervalCount = 0;
    this.loaded = false;
    this.loadedError = false;
    this.messages = [
        'Сбор сведений...',
        'Генерация цитат...',
        'Создания мышиной популяции...',
        'Компиляция атмосферы...',
        'Курим...'
    ];
    this.stopLoading = function(error, message) {
        clearInterval(this.messagesInterval);
        this.$loadingMessage.html(message);

        if (false == error) {
            this.$loading.fadeOut({
                duration: 800,
                easing: 'linear',
                complete: function() {
                    $(this).remove();
                }
            });
        }
    };
    this.messagesIntervalHandler = function() {
        if (this.messagesIntervalCount <= this.messages.length) {
            this.$loadingMessage.html(this.messages[this.messagesIntervalCount]);
            this.messagesIntervalCount++;
        } else {
            if (true == this.loaded) {
                this.stopLoading(
                    this.loadedError,
                    this.loadedError
                        ? 'Извините! Мы в плохом настроении - попробуйте позже!'
                        : 'Готово!'
                );
            } else {
                // Repeat loop of interval
                this.messagesIntervalCount = 0;
            }
        }
    };
    this.init = function() {
        this.messagesInterval = setInterval(
            $.proxy(this.messagesIntervalHandler, this),
            this.messagesIntervalTime
        );

        var _self = this;
        RR.$container.load(url, function(response, status, xhr) {
            _self.loadedError = ("error" == status);
            _self.loaded = true;
        });
    };

    this.init();
};

/**
 * Scroller
 * @constructor
 */
RR.pageScroller = function() {
    var defaultOptions = {
            scrollersClass: 'rr-scroller',
            animationTime: 500
        },
        _that = this,
        _onScrollerClick = function (e) {
            e.preventDefault();

            _that.scrollToId($(this).attr('href'));
        };

    this.scrollToId = function(id) { 
        history.pushState({}, "", id);

        $('html, body').animate({
            scrollTop: $(id).offset().top
        }, defaultOptions.animationTime);
    };
    this.init = function () {
        var hash = window.location.hash;

        if (hash) {
            this.scrollToId(hash);
        }

        $('body').on(
            'click',
            '.' + defaultOptions.scrollersClass,
            _onScrollerClick
        );

        // Parallax
        $(window).scroll(function() {
            $('[data-type="background"], [data-type="content"]').each(function(){

                var $obj  = $(this),
                    $window = $(window),
                    yPos    = ($window.scrollTop() / $obj.data('speed')),
                    direction = (undefined == $obj.data('direction'))
                        ? '+'
                        : $obj.data('direction')
                    ;

                if ('background' === $obj.data('type')) {
                    // Move the background
                    $obj.css({backgroundPosition: '50% '+ direction + yPos + '%'});
                } else if ('content' === $obj.data('type')) {
                    // Move the content and fade to black
                    $obj.css({top: yPos * 4 + 'px'});
                }
            });
        });
    };

    this.init();
};

/**
 * Flasher
 * @constructor
 */
RR.flasher = function() {
    this.messages = [];
    this.count = 0;
};
    RR.flasher.prototype.init = function() {

        for (var index in this.messages) {
            var $section = getElementById(this.messages[index].section);
            var $message = getElementById(RR.flasher.defaultOptions.noticePrototypeId)
                .clone()
                .attr('id', this.messages[index].message)
                .prepend(this.messages[index].message)
                .addClass(this.messages[index].level);

            console.log($section);
            console.log($message);
            $message.on('click', '.dismiss', this._onMessageDismiss);

            $section.find('header').prepend(
                $message.fadeIn(
                    RR.flasher.defaultOptions.animationTime
                )
            );
        }
    };
    RR.flasher.prototype.addMessage = function(section, level, message) {
        this.messages.push({section: section, level: level, message: message});
        this.count++;
    };
    RR.flasher.prototype._onMessageDismiss = function(e) {
        e.preventDefault();

        $(this).parents('.' + $(this).data('dismiss')).remove();
    };
    RR.flasher.defaultOptions = {
        noticePrototypeId: 'rr-flash-notice-prototype',
        translationsHolderClass: 'rr-notice-translations',
        animationTime: 2000
    };