<?php
$current_id = get_the_ID();
// Query random posts
$the_query = new WP_Query( array(
	'post_type'      => 'post',
	'orderby'        => 'rand',
	'posts_per_page' => 3,
	'post__not_in'   => array( get_the_ID() )
) ); ?>

<?php
// If we have posts lets show them
if ( $the_query->have_posts() ) : ?>

    <div id="ab-related-posts" class="ab-related-posts" style="display: block;">
        <h3 class="ab-related-posts-headline"><?php echo esc_html( __( 'More like this', 'ab-posts-render' ) ); ?></h3>
        <div class="ab-related-posts-items ab-related-posts-items-visual">
			<?php
			// Loop through the posts
			while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                <div class="ab-related-posts-post ab-related-posts-post0 ab-related-posts-post-thumbs"
                     data-post-id="<?php echo esc_attr( $current_id ); ?>">
                    <img class="ab-related-posts-post-img"
                         src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'post-thumbnail' ) ); ?>">
                    <h6 class="ab-related-posts-post-title">
                        <a class="ab-related-posts-post-a" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"
                           data-origin="<?php echo esc_attr( $current_id ); ?>"><?php the_title(); ?></a>
                    </h6>
                </div>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
        </div>
    </div>

<?php endif; ?>
