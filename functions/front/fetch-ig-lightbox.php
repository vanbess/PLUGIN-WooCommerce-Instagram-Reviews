<?php

/**
 * Returns IG bost lightbox and associated data via Ajax
 */

add_action('wp_ajax_nopriv_sbwcir_lb_data', 'sbwcir_lb_data');
add_action('wp_ajax_sbwcir_lb_data', 'sbwcir_lb_data');

function sbwcir_lb_data() {

    if (isset($_POST['review_id'])) :

        $rev_id = $_POST['review_id'];

        // get post meta
        $profile_pic = get_post_meta($rev_id, 'profile_pic', true);
        $post_link = get_post_meta($rev_id, 'post_link', true);
        $username = get_post_meta($rev_id, 'username', true);
        $posted_on = get_post_meta($rev_id, 'post_date', true);
        $image = get_post_meta($rev_id, 'post_img', true);
        $post_txt = get_post_meta($rev_id, 'post_txt', true);
        $comment_count = get_post_meta($rev_id, 'comment_count', true);
        $like_count = get_post_meta($rev_id, 'likes_count', true);


        // get excluded strings
        $excluded = trim(get_option('sbwcir_strip_strings'));

?>

        <div class="ig_lb_inner">

            <!-- lb inner top -->
            <div class="ig_lb_top">
                <!-- profile photo -->
                <span class="ig_prof_photo">
                    <img src="<?php echo $profile_pic; ?>" alt="<?php echo str_replace($excluded, '', $username); ?>">
                </span>

                <span class="ig_name_dat_cont">
                    <!-- name -->
                    <span class="ig_prof_name">
                        <?php echo $username; ?>
                    </span>

                    <!-- date -->
                    <span class="ig_post_date">
                        <?php echo date('F j, Y', $posted_on); ?>
                    </span>
                </span>

                <!-- ig icon -->
                <a href="<?php echo $post_link ?>" target="_blank" title="<?php pll_e('View on Instagram'); ?>">
                    <img class="ig_lb_logo" src="<?php echo SBWCIR_URL . 'img/ig_logo_black.png' ?>" alt="ig logo">
                </a>
            </div>

            <!-- lb inner text -->
            <div class="ig_lb_text">

                <?php
                $strlength = strlen($post_txt);
                if ($strlength > 150) : ?>

                    <!-- short text -->
                    <span class="ig_lb_txt_substr">
                        <?php echo substr($post_txt, 0, 150); ?>...<br><a href="javascript:void(0)">more</a>
                    </span>

                    <!-- long text -->
                    <span class="ig_lb_txt_more" style="display: none;">
                        <?php echo $post_txt ?>
                    </span>

                <?php else : ?>

                    <!-- long text -->
                    <span class="ig_lb_txt_more">
                        <?php echo $post_txt ?>
                    </span>

                <?php endif; ?>
            </div>

            <!-- lb inner mid -->
            <div class="ig_lb_mid">

                <!-- likes -->
                <span class="ig_lb_likes">
                    <img src="<?php echo SBWCIR_URL . 'img/ig_hart.png' ?>" alt="ig likes">
                </span>

                <!-- likes count -->
                <span class="ig_lb_likes_count">
                    <?php echo $like_count; ?>
                </span>

                <!-- comments -->
                <span class="ig_lb_comments">
                    <img src="<?php echo SBWCIR_URL . 'img/ig_comments.png' ?>" alt="ig comments">
                </span>

                <!-- comment count -->
                <span class="ig_lb_comment_count">
                    <?php echo $comment_count; ?>
                </span>

                <span class="ig_lb_share_cont">
                    <!-- share -->
                    <span class="ig_lb_share">
                        <a href="mailto:your@email.com?&subject=&body=<?php echo $post_link ?>" title="Share">
                            <img src="<?php echo SBWCIR_URL . 'img/ig_arrow.png' ?>" alt="ig share">
                        </a>
                    </span>

                    <!-- share text -->
                    <span class="ig_lb_share_text">
                        <?php pll_e('Share'); ?>
                    </span>
                </span>

            </div>

            <!-- lb inner photo -->
            <div id="lg_lb_bottom_<?php echo $rev_id; ?>" class="ig_lb_bottom">
                <img src="<?php echo $image; ?>" alt="if photo">
            </div>

            <script>
                jQuery(function($) {
                    // show more text inside lightbox on click
                    $('span.ig_lb_txt_substr > a').click(function(e) {
                        e.preventDefault();

                        $(this).parent().hide();
                        $('.ig_lb_txt_more').show();

                    });
                });
            </script>

    <?php endif;

    wp_die();
}
