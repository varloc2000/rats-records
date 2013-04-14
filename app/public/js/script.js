/* 
 * All scripts to project rats-records
 */

$(function() {
    $('.rr-top-menu .dropdown > a').on('click', function(e) {
        e.preventDefault();

        var sign = '+=';
        if ($(this).parent('li').hasClass('expanded')) {
            sign = '-=';
            $(this).parent('li').removeClass('expanded');
        } else {
            $(this).parent('li').addClass('expanded');
        }

        $(this).siblings('ul').find('li').each(function(index) {
            var topOffset = (index + 1) * $(this).outerHeight() + ((index + 1) * 5);
            console.log(topOffset);
            $(this).animate({
                top: sign + topOffset,
                boxShadow: '+=' == sign ? '0 0 7px #000' : '0 0 0'
            }, 200);
        })
    });

    /**
     * Add once animation to smiles
     */
    $('span.smile').parent().on('hover', function() {
        $(this).find('.smile').addClass('rotated');
    });
});