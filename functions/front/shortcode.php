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
		
		$image_height = "250px";
		$image_radius = '';
		$image_width = '';
		$image_size = 'medium';
		
      // Repeater options
      $repater['id'] = 'iggallery-'.rand();
      $repater['type'] = 'slider';
      $repater['style'] = 'none';
      $repater['class'] = '';
      $repater['visibility'] = '';
      $repater['slider_style'] = 'simple';
      $repater['slider_nav_position'] = 'outside';
      $repater['slider_bullets'] = 'true';
      $repater['slider_nav_color'] = '';
      $repater['auto_slide'] = '5000';
      $repater['row_spacing'] = 'small';
      $repater['row_width'] = '';
      $repater['columns'] = '4';
      $repater['columns__sm'] = '';
      $repater['columns__md'] = '';
	  
	  $classes_col = array('gallery-col','col');
	  $classes_box = array('box','has-hover','gallery-box','box-none');
      $classes_image = array('box-image');
      if($image_height) $classes_image[] = 'image-cover';
	  
	  $css_args_img = array(
        array( 'attribute' => 'border-radius', 'value' => $image_radius, 'unit' => '%'),
        array( 'attribute' => 'width', 'value' => $image_width, 'unit' => '%' ),
        array( 'attribute' => 'padding-top', 'value' => $image_height),
      );
	  
	  $lightbox_content = "";
	  
	  get_flatsome_repeater_start($repater);

        if ($ig_query->have_posts()) : ?>
			<?php while ($ig_query->have_posts()) : $ig_query->the_post();

				$title = get_the_title();

				if (in_array($title, $reviews)) : 
					$post_img = get_post_meta(get_the_ID(), 'post_thumb', true);
					$post_img_lrg = get_post_meta(get_the_ID(), 'post_img', true);
					//$image_output = wp_get_attachment_image( $id, $image_size);
					$image_output = '<img src="' . $post_img. '">';

					$link_start = '<a href="#igr' . get_the_ID() . '">';
					$link_end = '</a>';
					?>
						<div class="<?php echo implode(' ', $classes_col); ?>">
						  <div class="col-inner">
							<?php echo $link_start; ?>
							<div class="<?php echo implode(' ', $classes_box); ?>">
							  <div class="<?php echo implode(' ', $classes_image); ?>" <?php echo get_shortcode_inline_css($css_args_img); ?>>
								<?php echo $image_output; ?>
							  </div>
							</div>
							<?php echo $link_end; ?>
						  </div>
						 </div>
			<?php 
					$lightbox_content .= '<div id="igr' . get_the_ID() . '" class="lightbox-by-id lightbox-content lightbox-white mfp-hide" style="max-width:600px ;padding:20px">hello test content ' . get_the_ID() . '
<img src="' . $post_img_lrg . '"></div>';
				
				endif;

			endwhile;
			wp_reset_postdata(); ?>
        <?php endif;
		
    get_flatsome_repeater_end($repater);
	
	echo $lightbox_content;
	echo '
<style>
.row-small>.flickity-viewport>.flickity-slider>.col, .row-small>.col {padding: 0 3px 19.6px}
</style>
	';

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
