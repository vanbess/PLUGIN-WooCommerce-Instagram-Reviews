<?php

/**
 * Register settings page
 */

// register/add settings page
function sbwcir_settings_page_register() {
    add_submenu_page('/edit.php?post_type=instagram_review', 'Instagram Reviews Settings/Import', 'Import/Settings', 'manage_options', 'sbwc-ir-settings', 'sbwcir_settings_page_render', 1);
}

// render settings page html
function sbwcir_settings_page_render() { ?>

    <div id="sbwc-ir-settings">

        <h3><?php pll_e('Instagram Reviews Settings'); ?></h3>

        <hr>

        <!-- shortcodes -->
        <h3><?php pll_e('SHORTCODES'); ?></h3>
        <div class="sbwcir_settings_cont">
            <p><b><?php pll_e('Display ALL Instagram Reviews: '); ?></b><br><br>
                <input readonly type="text" value="[sbwcir_reviews]" style="width: 99%;">
            </p>
            <p><b><?php pll_e('Display specific Instagram Reviews using review shortcodes: '); ?></b><br><br>
                <input type="text" readonly value='[sbwcir_reviews ids="CHqMGxqhwQ4,CH045YRHbel,CIG5DLfge_B,CIISRNGAgFR,CIVDUDCnIfP,CHwv81tFCDU,CHYl_Y7FF7n"]' style="width:99%">
            </p>
        </div>

        <hr>
        
        <h3><?php pll_e('IMPORT AND PURGE'); ?></h3>
        <div class="sbwcir_settings_cont">

            <?php

            // insert links
            sbwcir_insert_subbed_links();

            // insert instagram post, fetch associated JSON and update each post accordingly
            if (get_option('sbwc_ir_json_links_array')) :


                foreach (get_option('sbwc_ir_json_links_array') as $ig_shortcode) :
					
					$ig_shortcode = trim($ig_shortcode);
					
                    // trim link so that we make sure it is valid for sending curl request
                    $link  = 'https://api.instacloud.io/v1?path=%2Fmedia%2Fshortcode%2F' . $ig_shortcode;

                    // get instagram post shortcode to use as post title
                   //$ig_shortcode = str_replace('https://api.instacloud.io/v1?path=%2Fmedia%2Fshortcode%2F', '', $link);

                    // insert post
                    $post_id = wp_insert_post([
                        'post_title' => $ig_shortcode,
                        'post_status' => 'publish',
                        'post_type' => 'instagram_review',
                        'meta_input' => [
                            'sbwcir_data_link' => $link,
                            'sbwcir_parsed' => 'No'
                        ]
                    ]);

                    // initiate curl request and retrieve post data JSON
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $link,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                    ));

                    $response = curl_exec($curl);
                    curl_close($curl);

                    // if response received, update post meta
                    if ($response) :

                        // insert raw JSON for future ref
                        update_post_meta($post_id, 'json_data', maybe_serialize($response));

                    endif;

                    if ($post_id) :
                        echo 'Instagram post inserted: ' . $post_id . '<br>';
                    else :
                        echo 'Instagram post could not be inserted';
                    endif;

                endforeach;

                // delete submitted links after post insertion has been successful
                delete_option('sbwc_ir_json_links_array');

            else : ?>
                <p><em><?php pll_e('You have not yet imported Instagram JSON links.'); ?></em></p>
            <?php endif;
            ?>
        </div>

        <!-- json links input -->
        <div class="sbwcir_settings_cont">
            <form action="" method="post">
                <label for="sbwcir_json_links"><?php pll_e('Add Instagram JSON links below, one per line:'); ?></label>
                <textarea name="sbwcir_json_links" id="sbwcir_json_links" cols="30" rows="10"><?php print_r(get_option('sbwc_ir_json_links_array'), true); ?></textarea>
                <input name="sbwcir_submit_json_links" id="sbwcir_submit_json_links" type="submit" value="<?php pll_e('Submit JSON Links'); ?>">
            </form>
        </div>

        <!-- purge reviews -->
        <div class="sbwcir_settings_cont">
            <label for="sbwcir_purge_reviews"><?php pll_e('Purge ALL Instagram Reviews?'); ?></label>
            <span class="sbwcir_info">
                <?php pll_e('If you would like to delete all Instagram Reviews and start from scratch, click the button below.'); ?>
            </span>
            <button id="sbwcir_purge_reviews"><?php pll_e('Purge All Reviews'); ?></button>

            <!-- confirm purge overlay -->
            <div id="sbwcir_conf_purge_ol" style="display: none;"></div>

            <!-- confirm purge dialogue -->
            <div id="sbwcir_conf_purge_dl" style="display: none;">
                <span id="sbwcir_purge_cancel">x</span>
                <p><b><?php pll_e('Are you sure you want to purge ALL Instagram Reviews? This cannot be undone!'); ?></b></p>
                <a id="sbwcir_conf_purge" href="javascript:void(0)"><?php pll_e('Yes, purge all reviews'); ?></a>
            </div>
        </div>
    </div>
<?php }

add_action('admin_menu', 'sbwcir_settings_page_register');

/**
 * Queue JS and CSS for admin
 */
add_action('admin_enqueue_scripts', 'sbwcir_admin_scripts');

function sbwcir_admin_scripts() {
    wp_enqueue_style('sbwcir-admin', SBWCIR_URL . 'css/admin.css');
    wp_enqueue_script('sbwcir-admin', SBWCIR_URL . 'js/admin.js', ['jquery']);
}


?>