<?php
/**
*  If 'Default Events Template' is selected in Settings -> The Events Calendar -> Theme Settings -> Events Template, 
*  then this file loads the page template for all for the individual 
*  event view.  Generally, this setting should only be used if you want to manually 
*  specify all the shell HTML of your ECP pages in this template file.  Use one of the other Theme 
*  Settings -> Events Template to automatically integrate views into your 
*  theme.
*
* You can customize this view by putting a replacement file of the same name (ecp-single-template.php) in the events/ directory of your theme.
*/

// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }
?>
<?php get_header(); ?>
<section id="content" class="clearfix <?php echo ss_framework_check_sidebar_position(); ?>">
	<div class="container">
		<header class="page-header">
			<h1 class="page-title"><?php the_title(); ?></h1>
		</header>
		<section id="main">
			<?php the_post(); global $post; ?>
			<article id="post-<?php the_ID() ?>" <?php post_class() ?>>
				<?php include(tribe_get_current_template()) ?>
			</article><!-- post -->
			<?php if(tribe_get_option('showComments','no') == 'yes'){ comments_template();} ?>
		</section>
		<?php get_sidebar(); ?>
	</div>
</section>
<?php get_footer(); ?>
