<?php

/**
 * CPT meta
 */

/**
 * Register meta box(es).
 */
function sbwcir_register_meta_boxes() {
    add_meta_box('sbwcir_meta_container', pll__('Review Meta'), 'sbwcir_display_callback', 'instagram_review');
}
add_action('add_meta_boxes', 'sbwcir_register_meta_boxes');

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function sbwcir_display_callback($post) {

    $rev_id = $post->ID;

    // data link
    $data_link = get_post_meta($rev_id, 'sbwcir_data_link', true);

    // parse json data and update username and post text
    $json_data = get_post_meta($rev_id, 'json_data', true);
    $data = json_decode($json_data, true);

    // get required data from json and update
    $profile_pic_data = $data['data']['user']['profile_picture'];
    $post_ig_link_data = $data['data']['link'];
    $username_data = $data['data']['caption']['from']['full_name'];
    $posted_on_data = $data['data']['created_time'];
    $image_data = $data['data']['images']['__original']['url'];
    $post_thumb_data = $data['data']['images']['thumbnail']['url'];
    $post_txt_data = $data['data']['caption']['text'];
    $comment_count_data = $data['data']['comments']['count'];
    $like_count_data = $data['data']['likes']['count'];

    // update review data if needed
    if (get_post_meta($rev_id, 'sbwcir_parsed', true) == 'No') :

        // handle sideloading and attaching of images to post first
        // upload post image and profile image to server
        file_put_contents(SBWCIR_PATH . 'images/profile_' . get_the_ID() . '.jpg', file_get_contents($profile_pic_data));
        file_put_contents(SBWCIR_PATH . 'images/post_' . get_the_ID() . '.jpg', file_get_contents($image_data));
        file_put_contents(SBWCIR_PATH . 'images/post_thumb_' . get_the_ID() . '.jpg', file_get_contents($post_thumb_data));

        // sideload images
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        // define img src
        $prof_img_src = SBWCIR_URL . 'images/profile_' . get_the_ID() . '.jpg';
        $post_img_src = SBWCIR_URL . 'images/post_' . get_the_ID() . '.jpg';
        $post_thumb_src = SBWCIR_URL . 'images/post_thumb_' . get_the_ID() . '.jpg';

        // sideload images and attach to post
        $prof_img_url = media_sideload_image($prof_img_src, get_the_ID(), '', 'src');
        $post_img_url = media_sideload_image($post_img_src, get_the_ID(), '', 'src');
        $post_thumb_url = media_sideload_image($post_thumb_src, get_the_ID(), '', 'src');

        // delete original images
        if ($prof_img_url && $post_img_url && $post_thumb_url) :
            array_map('unlink', glob(SBWCIR_PATH . 'images/*.jpg'));
        endif;

        update_post_meta($rev_id, 'profile_pic', $prof_img_url);
        update_post_meta($rev_id, 'post_link', $post_ig_link_data);
        update_post_meta($rev_id, 'username', $username_data);
        update_post_meta($rev_id, 'post_date', $posted_on_data);
        update_post_meta($rev_id, 'post_img', $post_img_url);
        update_post_meta($rev_id, 'post_thumb', $post_thumb_url);
        update_post_meta($rev_id, 'post_txt', sanitize_text_field($post_txt_data));
        update_post_meta($rev_id, 'comment_count', $comment_count_data);
        update_post_meta($rev_id, 'likes_count', $like_count_data);
        update_post_meta($rev_id, 'sbwcir_parsed', 'Yes');

    endif;


    // get meta
    $profile_pic = get_post_meta($rev_id, 'profile_pic', true);
    $post_ig_link = get_post_meta($rev_id, 'post_link', true);
    $username = get_post_meta($rev_id, 'username', true);
    $posted_on = get_post_meta($rev_id, 'post_date', true);
    $image = get_post_meta($rev_id, 'post_img', true);
    $post_thumb = get_post_meta($rev_id, 'post_thumb', true);
    $post_txt = get_post_meta($rev_id, 'post_txt', true);
    $comment_count = get_post_meta($rev_id, 'comment_count', true);
    $like_count = get_post_meta($rev_id, 'likes_count', true);
    $parsed = get_post_meta($rev_id, 'sbwcir_parsed', true);

?>

    <!-- post content -->
    <div class="data_cont">

        <!-- post link -->
        <div class="user_data">
            <label for="sbwcir_ig_link"><?php pll_e('Post link:'); ?></label>

            <?php if ($data_link) : ?>
                <input type="url" name="sbwcir_ig_link" id="sbwcir_ig_link" value="<?php echo $post_ig_link; ?>" readonly>
            <?php else : ?>
                No link present.
            <?php endif; ?>
        </div>

        <!-- data link -->
        <div class="user_data">
            <label for="sbwcir_data_link"><?php pll_e('Post data link:'); ?></label>

            <?php if ($data_link) : ?>
                <input type="url" name="sbwcir_data_link" id="sbwcir_data_link" value="<?php echo $data_link; ?>" readonly>
            <?php else : ?>
                No link present.
            <?php endif; ?>
        </div>

        <!-- profile pic -->
        <div class="user_data">
            <label for="sbwcir_user_prof_pic"><?php pll_e('Profile pic:'); ?></label>
            <?php if ($profile_pic) : ?>
                <img id="sbwcir_user_prof_pic" src="<?php echo $profile_pic ?>" alt="<?php echo $username ?>">
            <?php else : ?>
                No profile pic.
            <?php endif; ?>
        </div>

        <!-- user name -->
        <div class="user_data">
            <label for="sbwcir_user_name"><?php pll_e('User name:'); ?></label>
            <input type="text" name="sbwcir_user_name" id="sbwcir_user_name" value="<?php echo $username ?>" readonly>
        </div>

        <!-- post date -->
        <div class="user_data">
            <label for="sbwcir_post_date"><?php pll_e('Posted on:'); ?></label>
            <input type="text" name="sbwcir_post_date" id="sbwcir_post_date" value="<?php echo date('F j, Y', $posted_on); ?>" readonly>
        </div>

        <!-- post img -->
        <div class="user_data">
            <label for="sbwcir_post_img"><?php pll_e('Post image:'); ?></label>

            <?php if ($image) : ?>
                <img id="sbwcir_post_img" src="<?php echo $image; ?>" alt="">
            <?php else : ?>
                No image available.
            <?php endif; ?>
        </div>

        <!-- post thumb -->
        <div class="user_data">
            <label for="sbwcir_post_thumb"><?php pll_e('Post thumbnail:'); ?></label>

            <?php if ($post_thumb) : ?>
                <img id="sbwcir_post_thumb" src="<?php echo $post_thumb; ?>" alt="">
            <?php else : ?>
                No image available.
            <?php endif; ?>
        </div>

        <!-- post text -->
        <div class="user_data">
            <label for="sbwcir_post_text"><?php pll_e('Post text:'); ?></label>
            <textarea name="sbwcir_post_text" id="sbwcir_post_text" test="<?php echo $post_txt; ?>" readonly><?php echo $post_txt; ?></textarea>
        </div>

        <!-- comments -->
        <div class="user_data">
            <label for="sbwcir_post_comments"><?php pll_e('Post comment count:'); ?></label>
            <input type="number" name="sbwcir_post_comments" id="sbwcir_post_comments" value="<?php echo $comment_count; ?>" readonly>
        </div>

        <!-- likes -->
        <div class="user_data">
            <label for="sbwcir_post_likes"><?php pll_e('Post like count:'); ?></label>
            <input type="number" name="sbwcir_post_likes" id="sbwcir_post_likes" value="<?php echo $like_count; ?>" readonly>
        </div>

        <!-- json data -->
        <div class="user_data">
            <label for="sbwcir_json_data"><?php pll_e('JSON Data:'); ?></label>
            <textarea name="sbwcir_json_data" id="sbwcir_json_data"><?php echo $json_data; ?></textarea>
        </div>

        <!-- json parsed -->
        <div class="user_data">
            <label for="sbwcir_parsed"><?php pll_e('JSON Data Parsed?:'); ?></label>
            <input type="text" name="sbwcir_parsed" id="sbwcir_parsed" value="<?php echo $parsed; ?>">
        </div>
    </div>

<?php }

/**
 * Save meta box content.
 *
 * @param int $rev_id Post ID
 */
function sbwcir_save_meta_box($rev_id) {
    // Save logic goes here. Don't forget to include nonce checks!
}
add_action('save_post', 'sbwcir_save_meta_box');

?>