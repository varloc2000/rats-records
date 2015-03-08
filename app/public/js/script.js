/**
 * Global Rats Records namespace
 * @required jQuery
 */
var RR = {
    $container: $('#rr-container'),
    rainbow: [
        'red',
        'orange',
        'yellow',
        'green',
        'blue',
        'blue-dark',
        'purple'
    ]
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
    this.messagesIntervalTime = 500;
    this.messagesIntervalCount = 0;
    this.loaded = false;
    this.loadedError = false;
    this.messages = [
        'Collecting textures...',
        'Create good advices...',
        'Collecting Rat King parameters...',
        'Compiling Rat King...',
        'Smoking...',
        'Drinking...'
    ];
    this.stopLoading = function(error, message) {
        clearInterval(this.messagesInterval);
        this.$loadingMessage.html(message);

        if (false == error) {
            this.$loading.fadeOut({
                duration: 800,
                easing: 'linear'
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
                        ? 'Sorry! But some error occurred while load content. Please retry later!'
                        : 'Done.'
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
 * Single rotate smiles class
 * @constructor
 */
RR.smileSingleRotate = function(selector) {
    this.selector = selector;
    this.$smiles = $(this.selector);
};
    RR.smileSingleRotate.prototype.init = function() {
        var _self = this;

        this.$smiles.parent().on('mouseover', function() {
            _self._onParentHover($(this), _self.$smiles);
        });
    };
    RR.smileSingleRotate.prototype._onParentHover = function(parent, smiles) {
        parent.find(smiles).addClass('rotated');
    };

/**
 * Dropdown menu
 * @constructor
 */
RR.menuDropdown = function(selector) {
    this.selector = selector;
};
    RR.menuDropdown.prototype.init = function() {
        $('body').on(
            'click',
            this.selector,
            this._onDropdownInitiatorClick
        );
    };
    RR.menuDropdown.prototype._onDropdownInitiatorClick = function(e) {
        e.preventDefault();

        var sign = '+=';
        if ($(this).parent('li').hasClass(RR.menuDropdown.defaultOptions.expandedClass)) {
            sign = '-=';
            $(this).parent('li').removeClass(RR.menuDropdown.defaultOptions.expandedClass);
        } else {
            $(this).parent('li').addClass(RR.menuDropdown.defaultOptions.expandedClass);
        }

        $(this).siblings('ul').find('li').each(function(index) {
            var topOffset = (index + 1) * $(this).outerHeight() + ((index + 1) * 7);
            
            console.log('Collapsed menu items top offsets:');
            console.log($(this).text() + ' : ' + topOffset);
            
            $(this).animate({
                top: sign + topOffset,
                boxShadow: '+=' == sign ? '0 10px 15px 0px #000' : '0 0 0'
            }, RR.menuDropdown.defaultOptions.animationTime);
        })
    };
    RR.menuDropdown.defaultOptions = {
        expandedClass: 'expanded',
        animationTime: 250
    };

/**
 * Scroller
 * @constructor
 */
RR.pageScroller = function() {
};
    RR.pageScroller.prototype.init = function() {
        // Fast scrolling
        $('body').on(
            'click',
            '.' + RR.pageScroller.defaultOptions.scrollersClass,
            this._onScrollerClick
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

                    if ($obj.hasClass('n-fade')) {
                        $obj.fadeTo(10, (1 / yPos * 10));
                    }
                }
            });
        });
    };
    RR.pageScroller.prototype._onScrollerClick = function(e) {
        e.preventDefault();

        var scrollTo = $(this).attr('href'); 

        history.pushState({}, "", scrollTo);

        $('html, body').animate({
            scrollTop: $(scrollTo).offset().top - 50
        }, RR.pageScroller.defaultOptions.animationTime);

    };
    RR.pageScroller.defaultOptions = {
        scrollersClass: 'rr-scroller',
        animationTime: 500
    };

/**
 * Scroller
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