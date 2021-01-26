<?php

/**
 * Ajax to purge reviews via settings page
 */
add_action('wp_ajax_nopriv_sbwcir_purge_reviews', 'sbwcir_purge_reviews');
add_action('wp_ajax_sbwcir_purge_reviews', 'sbwcir_purge_reviews');

function sbwcir_purge_reviews() {

    if (isset($_POST['purge_ir_posts'])) :

        $ir_posts = get_posts([
            'post_type' => 'instagram_review',
            'posts_per_page' => -1
        ]);

        if (isset($ir_posts) && is_array($ir_posts) || is_object($ir_posts)) :

            foreach ($ir_posts as $post) {
                $deleted[] = wp_delete_post($post->ID, true);
            }
        else :
            pll_e('There are no Instagram reviews to be deleted.');
        endif;

        if (!empty($deleted)) :
            pll_e('All instagram reviews deleted');
        endif;

    endif;

    wp_die();
}
