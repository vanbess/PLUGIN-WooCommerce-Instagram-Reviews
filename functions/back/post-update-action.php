<?php

/**
 * Add function to action scheduler which will update IG post data
 */

//  check if any posts need to be updated
$ig_posts = get_posts(['post_type' => 'instagram_review', 'posts_per_page' => -1, 'meta_key' => 'sbwcir_parsed', 'meta_value' => 'No']);

//  schedule update action
if (!empty($ig_posts)) :
    function sbwcir_schedule_post_update() {
        if (as_next_scheduled_action('sbwcir_update_posts') === false) :
            as_schedule_single_action(strtotime('now'), 'sbwcir_update_posts');
        endif;
    }
    add_action('init', 'sbwcir_schedule_post_update');
endif;

// update posts callback
function sbwcir_update_posts_action() {

    // get relevant post ids
    $ir_query = new WP_Query([
        'post_type' => 'instagram_review',
        'posts_per_page' => -1,
        'meta_key' => 'sbwcir_parsed',
        'meta_value' => 'No'
    ]);

    if ($ir_query->have_posts()) :
        while ($ir_query->have_posts()) : $ir_query->the_post();

            $rev_id = get_the_ID();

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

            // update meta
            update_post_meta($rev_id, 'profile_pic', $prof_img_url);
            update_post_meta($rev_id, 'post_link', $post_ig_link_data);
            update_post_meta($rev_id, 'username', $username_data);
            update_post_meta($rev_id, 'post_date', $posted_on_data);
            update_post_meta($rev_id, 'post_img', $post_img_url);
            update_post_meta($rev_id, 'post_thumb', $post_thumb_url);
            update_post_meta($rev_id, 'post_txt', $post_txt_data);
            update_post_meta($rev_id, 'comment_count', $comment_count_data);
            update_post_meta($rev_id, 'likes_count', $like_count_data);
            update_post_meta($rev_id, 'sbwcir_parsed', 'Yes');
        endwhile;
        wp_reset_postdata();
    endif;

    // loop through ids (if present) and update post meta accordingly
    if (!empty($post_ids) && is_array($post_ids)) :

        foreach ($post_ids as $rev_id) :



        endforeach;
    endif;
}

add_action('sbwcir_update_posts', 'sbwcir_update_posts_action');
