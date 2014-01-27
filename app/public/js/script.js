/**
 * Global Rats Records namespace
 * @required jQuery
 */
var RR = {
    $container: $('html')
};

/**
 * Single rotate smiles class
 * @constructor
 */
RR.smileSingleRotate = function(selector) {
    this.selector = selector;
    this.$smiles = $(this.selector);
}
    RR.smileSingleRotate.prototype.init = function() {
        var _self = this;

        this.$smiles.parent().on('mouseover', function() {
            _self._onParentHover($(this), _self.$smiles);
        });
    }
    RR.smileSingleRotate.prototype._onParentHover = function(parent, smiles) {
        parent.find(smiles).addClass('rotated');
    }

/**
 * Dropdown menu
 * @constructor
 */
RR.menuDropdown = function(selector) {
    this.selector = selector;
}
    RR.menuDropdown.prototype.init = function() {
        $('body').on(
            'click',
            this.selector,
            this._onDropdownInitiatorClick
        );
    }
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
    }
    RR.menuDropdown.defaultOptions = {
        expandedClass: 'expanded',
        animationTime: 250
    }

/**
 * Scroller
 * @constructor
 */
RR.pageScroller = function() {
}
    RR.pageScroller.prototype.init = function() {
        $('body').on(
            'click',
            '.' + RR.pageScroller.defaultOptions.scrollersClass,
            this._onScrollerClick
        );
    }
    RR.pageScroller.prototype._onScrollerClick = function(e) {
        e.preventDefault();

        var scrollTo = $(this).attr('href'); 

        history.pushState({}, "", scrollTo);

        $('html, body').animate({
            scrollTop: $(scrollTo).offset().top - 50
        }, RR.pageScroller.defaultOptions.animationTime);

    }
    RR.pageScroller.defaultOptions = {
        scrollersClass: 'rr-scroller',
        animationTime: 500
    }