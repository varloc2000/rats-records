document.addEventListener("content_loaded", function() {
    var scroller = new RR.pageScroller();
    var submitter = new RR.formSubmitter();

    $('#rr-gallery').fotorama();

    $('.rr-header').waypoint({
        handler: function(direction) {
            $(this).toggleClass('rr-header-fixed-top', (direction === 'down'));
            $('.rr-top-menu').toggleClass('rr-top-menu-slim', (direction === 'down'));
        }
    });
});