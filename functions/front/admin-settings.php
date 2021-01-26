<?php

/**
 * Register settings page
 */

function sbwcir_settings_page_register() {
    add_submenu_page('/edit.php?post_type=instagram_review', 'Instagram Reviews Settings/Import', 'Import/Settings', 'manage_options', 'sbwc-ir-settings', 'sbwcir_settings_page_render', '');
}

function sbwcir_settings_page_render() { ?>

    <div id="sbwc-ir-settings">

        <h3><?php pll_e('Instagram Reviews Settings'); ?></h3>

        <hr>

        <!-- Previously submitted links -->
        <div class="sbwcir_settings_cont">
            <label for="sbwcir_json_links"><?php pll_e('Previously submitted JSON links:'); ?></label>

            <?php
            if (get_option('sbwc_ir_json_links_array')) :
                $counter = 1;
                foreach (get_option('sbwc_ir_json_links_array') as $link) : ?>
                    <span class="swbcir_subbed_link"><?php echo $counter . ': ' . $link; ?></span><br>
                <?php
                    $counter++;
                endforeach;
            else : ?>
                <p><em><?php pll_e('You have not yet submitted Instagram JSON links.'); ?></em></p>
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

        <!-- parse reviews -->
        <div class="sbwcir_settings_cont">
            <label for="sbwcir_parse_reviews"><?php pll_e('Parse Instagram Reviews?'); ?></label>
            <span class="sbwcir_info">
                <?php pll_e('If you would like to parse unparsed Instagram JSON data you can do so here. Only previously unparsed Instagram posts will be processed.'); ?>
            </span>
            <button id="sbwcir_parse_reviews"><?php pll_e('Parse Reviews'); ?></button>

            <!-- parse log -->
            <div id="sbwcir_parse_log"></div>
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