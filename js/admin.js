jQuery(document).ready(function($) {
    
    // confirm purge
    $('#sbwcir_conf_purge').click(function(e) {
        e.preventDefault();
        var data = {
            'action': 'sbwcir_purge_reviews',
            'purge_ir_posts': true
        };
        $.post(ajaxurl, data, function(response) {
            alert(response);
            location.reload();
        });
    });
    // show purge dialogue
    $('#sbwcir_purge_reviews').click(function(e) {
        e.preventDefault();
        $('#sbwcir_conf_purge_ol, #sbwcir_conf_purge_dl').show();
    });

    // hide purge dialogue
    $('#sbwcir_purge_cancel, #sbwcir_conf_purge_ol').click(function(e) {
        e.preventDefault();
        $('#sbwcir_conf_purge_ol, #sbwcir_conf_purge_dl').hide();
    });

});