<?php
/**
 *  If 'Default Events Template' is selected in Settings -> The Events Calendar -> Theme Settings -> Events Template,
 *  then this file loads the page template for all ECP views except for the individual
 *  event view.  Generally, this setting should only be used if you want to manually
 *  specify all the shell HTML of your ECP pages in this template file.  Use one of the other Theme
 *  Settings -> Events Template to automatically integrate views into your
 *  theme.
 *
 * You can customize this view by putting a replacement file of the same name (ecp-page-template.php) in the events/ directory of your theme.
 */

// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

?>
<?php get_header(); ?>
<section id="content" class="clearfix <?php echo ss_framework_check_sidebar_position(); ?>">
	<div class="container">
		<header class="page-header">
			<h1 class="page-title"><?php tribe_events_title(); ?></h1>
			<?php if(!tribe_is_day()): // day view doesn't have a grid ?>
			<nav id="event-list-calendar">
				<a class='list' href='<?php echo tribe_get_listview_link(); ?>'><?php _e('List View', 'tribe-events-calendar')?></a>
				<a class='calendar' href='<?php echo tribe_get_gridview_link(); ?>'><?php _e('Calendar View', 'tribe-events-calendar')?></a>
			</nav>
			<?php endif; ?>
		</header>
		<?php include(tribe_get_current_template()); ?>
	</div>
</section>
<?php get_footer(); ?>
