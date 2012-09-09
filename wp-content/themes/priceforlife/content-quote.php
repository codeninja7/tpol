<?php
$thumbnail_size = isset( $GLOBALS['post-carousel'] ) ? 'blog-post-thumb' : 'blog-post';
?>

<?php if( has_post_thumbnail() ): ?>

<?php $post_thumbnail_img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), apply_filters('ss_content_thumbnail_size', $thumbnail_size, $post) ); ?>
<?php $post_thumbnail_data = ss_framework_get_the_post_thumbnail_data( $post->ID ); ?>

<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
    <img src="<?php echo $post_thumbnail_img[0]; ?>" alt="<?php echo $post_thumbnail_data['alt']; ?>" title="<?php echo $post_thumbnail_data['title']; ?>" class="entry-image <?php echo $post_thumbnail_data['class']; ?>">
</a>

<?php endif; ?>

<?php $type = isset( $GLOBALS['post-carousel'] ) ? ' type="simple"' : null ?>

<div class="entry-body">

	<?php echo do_shortcode('[quote author="' . ss_framework_get_custom_field( 'ss_quote_author', $post->ID ) . '"' . $type . ']' . ss_framework_get_custom_field( 'ss_quote', $post->ID ) . '[/quote]'); ?>

	<?php echo ss_framework_post_content(); ?>	

</div><!-- end .entry-body -->

<div class="entry-meta">

	<?php echo ss_framework_post_meta(); ?>

</div><!-- end .entry-meta -->