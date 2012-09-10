<?php
$thumbnail_size = isset( $GLOBALS['post-carousel'] ) ? 'blog-post-thumb' : 'blog-post';
?>

<?php if( has_post_thumbnail() && !is_single() ): ?>

<?php $post_thumbnail_img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), apply_filters('ss_content_thumbnail_size', $thumbnail_size, $post) ); ?>
<?php $post_thumbnail_data = ss_framework_get_the_post_thumbnail_data( $post->ID ); ?>

    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
        <img src="<?php echo $post_thumbnail_img[0]; ?>" alt="<?php echo $post_thumbnail_data['alt']; ?>" title="<?php echo $post_thumbnail_data['title']; ?>" class="entry-image <?php echo $post_thumbnail_data['class']; ?>">
    </a>

<?php else: ?>
    <div class="entry-video">

        <?php

        if( ss_framework_get_custom_field( 'ss_video_mp4', $post->ID ) || ss_framework_get_custom_field( 'ss_video_webm', $post->ID ) || ss_framework_get_custom_field( 'ss_video_ogg', $post->ID ) ) {

            $shortcode = '[video';

                if( ss_framework_get_custom_field( 'ss_video_mp4', $post->ID ) && !isset( $GLOBALS['post-carousel'] ) )
                    $shortcode .= ' mp4="' . ss_framework_get_custom_field( 'ss_video_mp4', $post->ID ) . '"';

                if( ss_framework_get_custom_field( 'ss_video_webm', $post->ID ) )
                    $shortcode .= ' webm="' . ss_framework_get_custom_field( 'ss_video_webm', $post->ID ) . '"';

                if( ss_framework_get_custom_field( 'ss_video_ogg', $post->ID ) )
                    $shortcode .= ' ogg="' . ss_framework_get_custom_field( 'ss_video_ogg', $post->ID ) . '"';

                if( ss_framework_get_custom_field( 'ss_video_preview', $post->ID ) )
                    $shortcode .= ' poster="' . ss_framework_get_custom_field( 'ss_video_preview', $post->ID ) . '"';

                if( ss_framework_get_custom_field( 'ss_video_aspect_ratio', $post->ID ) )
                    $shortcode .= ' aspect_ratio="' . ss_framework_get_custom_field( 'ss_video_aspect_ratio', $post->ID ) . '"';

            $shortcode .= ']';

            echo do_shortcode( $shortcode );

        } elseif( ss_framework_get_custom_field( 'ss_video_external', $post->ID ) ) {

            echo do_shortcode( ss_framework_get_custom_field( 'ss_video_external', $post->ID ) );

        }

        ?>

    </div><!-- end .entry-video -->
<?php endif; ?>

<div class="entry-body">

    <?php if( !is_single() ): ?>
	<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__('Permalink to %s', 'ss_framework'), the_title_attribute('echo=0') ); ?>" rel="bookmark">
		<h1 class="title"><?php the_title(); ?></h1>
	</a>
    <?php endif; ?>

	<?php echo ss_framework_post_content(); ?>

</div><!-- end .entry-body -->

<div class="entry-meta">

	<?php echo ss_framework_post_meta(); ?>

</div><!-- end .entry-meta -->