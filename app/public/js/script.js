/**
 * Global Rats Records namespace
 * @required jQuery
 */
var RR = {
    $container: $('#rr-container'),
    translationsContainerId: 'rr-translations',
    _getTranslatedMessage: function (id, cssClass) {
        return $('<span />', {
            "class": cssClass,
            text: $('#' + this.translationsContainerId).find('#' + id).html()
        }); 
    }
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

            document.dispatchEvent(new CustomEvent("content_loaded"));
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
 * formSubmitter
 * @constructor
 */
RR.formSubmitter = function() {
    var defaultOptions = {
            submitBtnClass: 'rr-submiter',
            errorFieldClass: 'rr-field-error',
            errorClass: 'rr-error'
        },
        _that = this,
        _onSubmitterClick = function (e) {
            e.preventDefault();

            _that.submitForm($(this), $(this).parents('form'));
        };

    this.setFormErrors = function(errors) {
        for (var fieldId in errors) {
            if (errors.hasOwnProperty(fieldId)) {
                $('#' + fieldId)
                    .addClass(defaultOptions.errorFieldClass)
                    .before(
                        RR._getTranslatedMessage(errors[fieldId], defaultOptions.errorClass)
                    );
            }
        }
    };
    this.removeFormErrors = function($form) {
        $form
            .find('input, textarea')
            .removeClass(defaultOptions.errorFieldClass);

        $form
            .find('.' + defaultOptions.errorClass)
            .remove();
    };
    this.submitForm = function($btn, $form) {
        $btn.attr('disabled', 'disabled').addClass('loading');
        _that.removeFormErrors($form);

        $.post(
            $form.attr('action'),
            $form.serialize(),
            function (data) {
                $btn.removeAttr('disabled').removeClass('loading');

                if (false === data.success) {
                    _that.setFormErrors(data.errors);
                } else {
                    $btn.addClass('loaded');
                    console.log(data);
                }
            },
            'json'
        );
    };
    this.init = function () {
        $('form').on(
            'click',
            '.' + defaultOptions.submitBtnClass,
            _onSubmitterClick
        );
    };

    this.init();
};

/**
 * Flasher
 * @constructor
 */
RR.flasher = function() {
    var defaultOptions = {
            noticePrototypeId: 'rr-flash-notice-prototype',
            animationTime: 2000
        },
        _that = this,
        messages = [],
        count = 0;

    this.init = function() {

        for (var index in messages) {
            var $section = $('#' + messages[index].section);
            var $message = $('#' + defaultOptions.noticePrototypeId)
                .clone()
                .attr('id', messages[index].message)
                .prepend(messages[index].message)
                .addClass(messages[index].level);

            console.log($section);
            console.log($message);

            $section.find('header').prepend(
                $message.fadeIn(
                    defaultOptions.animationTime
                )
            );
        }
    };
    this.addMessage = function(section, level, message) {
        messages.push({section: section, level: level, message: message});
        this.count++;
    };
    this.addErrorMessage = function(section, message) {
        this.addMessage(section, 'error', message);
    };
};
