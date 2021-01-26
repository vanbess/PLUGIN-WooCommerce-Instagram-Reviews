<?php

/**
 * Returns IG bost lightbox and associated data via Ajax
 */

add_action('wp_ajax_nopriv_sbwcir_lb_data', 'sbwcir_lb_data');
add_action('wp_ajax_sbwcir_lb_data', 'sbwcir_lb_data');

function sbwcir_lb_data() {

    if (isset($_POST['review_id'])) :

        $review_id = $_POST['review_id'];

        $profile_pic = get_post_meta($review_id, 'profile_pic', true);
        $username = get_post_meta($review_id, 'username', true);
        $posted_on = get_post_meta($review_id, 'post_date', true);
        $image = get_post_meta($review_id, 'post_img', true);
        $post_txt = get_post_meta($review_id, 'post_txt', true);
        $comment_count = get_post_meta($review_id, 'comment_count', true);
        $like_count = get_post_meta($review_id, 'likes_count', true); ?>

        <div class="ig_lb_inner">

            <!-- lb inner top -->
            <div class="ig_lb_top">
                <!-- profile photo -->
                <span class="ig_prof_photo">
                    <img src="<?php echo $profile_pic; ?>" alt="<?php echo $username; ?>">
                </span>

                <!-- name -->
                <span class="ig_prof_name">
                    <?php echo $username; ?>
                </span>

                <!-- date -->
                <span class="ig_post_date">
                    <?php echo $posted_on; ?>
                </span>

                <!-- ig icon -->
                <img class="ig_lb_logo" src="<?php echo SBWCIR_URL . 'img/ig_logo_black.png' ?>" alt="ig logo">
            </div>

            <!-- lb inner text -->
            <div class="ig_lb_text">
                <?php echo $post_txt ?>
            </div>

            <!-- lb inner mid -->
            <div class="ig_lb_mid">

                <!-- likes -->
                <span class="ig_lb_likes">
                    <img src="" alt="ig likes">
                </span>

                <!-- comments -->
                <span class="ig_lb_comments">
                    <img src="" alt="ig comments">
                </span>

                <!-- share -->
                <span class="ig_lb_share"></span>

            </div>

            <!-- lb inner photo -->
            <div id="lg_lb_bottom_<?php echo $review_id; ?>" class="ig_lb_bottom">
            </div>

    <?php endif;

    wp_die();
}
