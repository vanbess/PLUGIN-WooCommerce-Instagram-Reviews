// jquery
jQuery(window).load(function () {

    var $ = jQuery;

    // vertically align ig thumbnails in carousel
    var cont_height = $('.glide__track').innerHeight();

    $('ul.glide__slides > li > img.ig_carousel_thumb').each(function () {
        var img_height = $(this).height();
        var top = (cont_height - img_height) / 2;
        $(this).css('position', 'relative');
        $(this).css('top', top);
    });

    // show ig post image et al on click
    $('li.glide__slide').click(function (e) {
        e.preventDefault();

        // get post/review id
        var post_id = $(this).attr('post-id');
        var ajaxurl = $(this).attr('aju');

        // console.log(post_id);
        // console.log(ajaxurl);

        var data = {
            'action': 'sbwcir_lb_data',
            'review_id': post_id
        };
        $.post(ajaxurl, data, function (response) {
            $('#ig_lb_proper').html(response);
            $('#ig_lb_overlay, #ig_lb_proper').show();
        });


    });

    // hide ig overlay and lightbox on overlay click
    $('#ig_lb_overlay').click(function (e) {
        e.preventDefault();
        $('#ig_lb_overlay, #ig_lb_proper').hide();
    });


});