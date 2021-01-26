<?php

// function to render shortcode content
function sbwcir_render_reviews($atts = array(), $content = null, $tag = '') {
    $reviews = $atts['ids'];

    // if review tags/shortcodes specified
    if ($reviews) :

        $reviews = explode(',', $reviews);

        $ig_query = new WP_Query([
            'post_type' => 'instagram_review',
            'post_status' => 'publish',
            'posts_per_page' => -1
        ]);

        if ($ig_query->have_posts()) : ?>
            <div id="sbwcir_carousel_container">
                <div class="glide">
                    <div class="glide__track" data-glide-el="track">
                        <ul class="glide__slides">
                            <?php while ($ig_query->have_posts()) : $ig_query->the_post();

                                $title = get_the_title();
                                $post_img = get_post_meta(get_the_ID(), 'post_thumb', true);
                                $post_img_lrg = get_post_meta(get_the_ID(), 'post_img', true);

                                if (in_array($title, $reviews)) : ?>
                                    <li class="glide__slide" post-id="<?php echo get_the_ID(); ?>" aju="<?php echo admin_url('admin-ajax.php'); ?>">
                                        <img class="ig_carousel_logo" src="<?php echo SBWCIR_URL . 'img/ig_logo_grey.png' ?>" alt="ig logo">
                                        <img class="ig_carousel_thumb" src="<?php echo $post_img ?>" lrg="<?php echo $post_img_lrg ?>" alt="<?php echo $title; ?>">
                                    </li>
                            <?php endif;

                            endwhile;
                            wp_reset_postdata(); ?>

                        </ul>
                    </div>
                    <div class="glide__arrows" data-glide-el="controls">
                        <button class="glide__arrow glide__arrow--left" data-glide-dir="<">&#171;</button>
                        <button class="glide__arrow glide__arrow--right" data-glide-dir=">">&#187;</button>
                    </div>
                </div>
            </div><!-- end sbwcir_carousel_container -->

            <!-- lightbox overlay -->
            <div id="ig_lb_overlay" style="display: none;"></div>

            <!-- lightbox actual -->
            <div id="ig_lb_proper" style="display: none;"></div>

            <script>
                // carousel setup
                new Glide('.glide', {
                    type: 'carousel',
                    startAt: 1,
                    perView: 6,
                    gap: 0,
                    peek: {
                        before: 0,
                        after: 0
                    },
                    breakpoints: {
                        1024: {
                            perView: 5
                        },
                        800: {
                            perView: 3
                        },
                        600: {
                            perView: 2
                        },
                        414: {
                            perView: 2
                        }
                    }
                }).mount();
            </script>
        <?php endif;

    // if review tags/shortcodes NOT specified
    else :

        $ig_query = new WP_Query([
            'post_type' => 'instagram_review',
            'post_status' => 'publish',
            'posts_per_page' => -1
        ]);

        if ($ig_query->have_posts()) : ?>
            <div id="sbwcir_carousel_container">
                <div class="glide">
                    <div class="glide__track" data-glide-el="track">
                        <ul class="glide__slides">
                            <?php while ($ig_query->have_posts()) : $ig_query->the_post();

                                $title = get_the_title();
                                $post_img = get_post_meta(get_the_ID(), 'post_thumb', true);
                                $post_img_lrg = get_post_meta(get_the_ID(), 'post_img', true);
                            ?>

                                <li class="glide__slide" post-id="<?php echo get_the_ID(); ?>" aju="<?php echo admin_url('admin-ajax.php'); ?>">
                                    <img class="ig_carousel_logo" src="<?php echo SBWCIR_URL . 'img/ig_logo_grey.png' ?>" alt="ig logo">
                                    <img class="ig_carousel_thumb" src="<?php echo $post_img ?>" lrg="<?php echo $post_img_lrg ?>" alt="<?php echo $title; ?>">
                                </li>

                            <?php endwhile;
                            wp_reset_postdata(); ?>
                        </ul>
                    </div>
                    <div class="glide__arrows" data-glide-el="controls">
                        <button class="glide__arrow glide__arrow--left" data-glide-dir="<">&#171;</button>
                        <button class="glide__arrow glide__arrow--right" data-glide-dir=">">&#187;</button>
                    </div>
                </div>
            </div>

            <!-- lightbox overlay -->
            <div id="ig_lb_overlay" style="display: none;"></div>

            <!-- lightbox actual -->
            <div id="ig_lb_proper" style="display: none;"></div>

            <script>
                // carousel setup
                new Glide('.glide', {
                    type: 'carousel',
                    startAt: 1,
                    perView: 6,
                    gap: 0,
                    peek: {
                        before: 0,
                        after: 0
                    },
                    breakpoints: {
                        1024: {
                            perView: 5
                        },
                        800: {
                            perView: 3
                        },
                        600: {
                            perView: 2
                        },
                        414: {
                            perView: 1
                        }
                    }
                }).mount();
            </script>
<?php endif;
    endif;
}

// shortcode to render review on frontend
add_shortcode('sbwcir_reviews', 'sbwcir_render_reviews');
