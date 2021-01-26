<?php

/**
 * Insert initial review based on submitted Instagram JSON links
 */
function sbwcir_insert_subbed_links() {
    if (isset($_POST['sbwcir_json_links'])) :

        // generate submitted links array
        $subbed_links = trim($_POST['sbwcir_json_links']);
        $subbed_links_arr = explode("\n", $subbed_links);

        // insert generated array as option, loop through array and insert posts
        if (is_array($subbed_links_arr) && !empty($subbed_links_arr)) :
            $links_inserted = update_option('sbwc_ir_json_links_array', $subbed_links_arr);
        endif;

        // display alert if reviews inserted successfully, else display error
        if ($links_inserted) : ?>
            <p class="sbwcir_success"><?php pll_e('Data links inserted.'); ?></p>
        <?php else : ?>
            <p class="sbwcir_error"><?php pll_e('Data links could not be inserted. Please reload the page and try again.'); ?></p>
<?php endif;
    endif;
}
?>