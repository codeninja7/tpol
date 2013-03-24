<?php
/**
 * The TEC template for a list of events. This includes the Past Events and Upcoming Events views
 * as well as those same views filtered to a specific category.
 *
 * You can customize this view by putting a replacement file of the same name (list.php) in the events/ directory of your theme.
 */

// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

?>

<section id="main">
	<?php if (have_posts()) : ?>
		<?php $hasPosts = true; $first = true; ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php global $more; $more = false; ?>

			<article id="post-<?php the_ID() ?>" <?php post_class('hentry clearfix') ?> itemscope itemtype="http://schema.org/Event">
				<div class="entry-body">
					<?php the_title('<h2 class="title" itemprop="name"><a href="' . tribe_get_event_link() . '" title="' . the_title_attribute('echo=0') . '" rel="bookmark">', '</a></h2>'); ?>
					<?php if (has_excerpt ()): ?>
						<?php the_excerpt(); ?>
					<?php else: ?>
						<?php the_content(); ?>
					<?php endif; ?>
				</div><!-- end .entry-body -->

				<div class="entry-meta" itemprop="location" itemscope itemtype="http://schema.org/Place">
					<ul>
						<?php if (tribe_is_multiday() || !tribe_get_all_day()): ?>
							<li>
								<span class="title"><?php _e('Start:', 'tribe-events-calendar') ?></span>
								<time itemprop="startDate" content="<?php echo tribe_get_start_date(); ?>"><?php echo tribe_get_start_date(null,true,'M j, Y'); ?></time>
							</li>
							<li>
								<span class="title"><?php _e('End:', 'tribe-events-calendar') ?></span>
								<time itemprop="endDate" content="<?php echo tribe_get_end_date(); ?>"><?php echo tribe_get_end_date(null,true,'M j, Y'); ?></time>
							</li>
						<?php else: ?>
							<li>
								<span class="title"><?php _e('Date:', 'tribe-events-calendar') ?></span>
								<time itemprop="startDate" content="<?php echo tribe_get_start_date(); ?>"><?php echo tribe_get_start_date(null,true,'M j, Y'); ?></time>
							</li>
						<?php endif; ?>

						<?php
						$venue = tribe_get_venue();
						if ( !empty( $venue ) ) :
							?>
							<li>
								<span class="title"><?php _e('Venue:', 'tribe-events-calendar') ?></span>
								<span itemprop="name">
									<? if( class_exists( 'TribeEventsPro' ) ): ?>
										<?php tribe_get_venue_link( get_the_ID(), class_exists( 'TribeEventsPro' ) ); ?>
									<? else: ?>
										<?php echo tribe_get_venue( get_the_ID() ) ?>
									<? endif; ?>
								</span>
							</li>
						<?php endif; ?>
						<?php
						$phone = tribe_get_phone();
						if ( !empty( $phone ) ) :
							?>
							<li>
								<span class="title"><?php _e('Phone:', 'tribe-events-calendar') ?></span>
								<span itemprop="telephone"><?php echo $phone; ?></span>
							</li>
						<?php endif; ?>
						<?php if (tribe_address_exists( get_the_ID() ) ) : ?>
							<li>
								<span class="title"><?php _e('Address:', 'tribe-events-calendar'); ?></span>
								<span>
									<?php echo tribe_get_full_address( get_the_ID() ); ?>
									<?php if( get_post_meta( get_the_ID(), '_EventShowMapLink', true ) == 'true' ) : ?>
									<br/><a class="gmap" itemprop="maps" href="<?php echo tribe_get_map_link(); ?>" title="Click to view a Google Map" target="_blank"><?php _e('Google Map', 'tribe-events-calendar' ); ?></a>
									<?php endif; ?>
								</span>
							</li>
						<?php endif; ?>
						<?php
						$cost = tribe_get_cost();
						if ( !empty( $cost ) ) :
							?>
							<li>
								<span class="title"><?php _e('Cost:', 'tribe-events-calendar') ?></span>
								<span itemprop="price"><?php echo $cost; ?></span>
							</li>
						<?php endif; ?>
					</ul>
				</div>
			</article> <!-- End article -->
		<?php endwhile;// posts ?>
	<?php else :?>
		<?php
		$tribe_ecp = TribeEvents::instance();
		if ( is_tax( $tribe_ecp->get_event_taxonomy() ) ) {
			$cat = get_term_by( 'slug', get_query_var('term'), $tribe_ecp->get_event_taxonomy() );
			if( tribe_is_upcoming() ) {
				$is_cat_message = sprintf(__(' listed under %s. Check out past events for this category or view the full calendar.','tribe-events-calendar'),$cat->name);
			} else if( tribe_is_past() ) {
				$is_cat_message = sprintf(__(' listed under %s. Check out upcoming events for this category or view the full calendar.','tribe-events-calendar'),$cat->name);
			}
		}
		?>
		<?php if(tribe_is_day()): ?>
			<?php printf( __('No events scheduled for <strong>%s</strong>. Please try another day.', 'tribe-events-calendar'), date_i18n('F d, Y', strtotime(get_query_var('eventDate')))); ?>
		<?php endif; ?>

		<?php if(tribe_is_upcoming()){ ?>
			<?php _e('No upcoming events', 'tribe-events-calendar');
			echo !empty($is_cat_message) ? $is_cat_message : ".";?>

		<?php }elseif(tribe_is_past()){ ?>
			<?php _e('No previous events' , 'tribe-events-calendar');
			echo !empty($is_cat_message) ? $is_cat_message : ".";?>
		<?php } ?>

	<?php endif; ?>

	<nav class="pagination">
		<?php
		// Display Previous Page Navigation
		if( tribe_is_upcoming() && get_previous_posts_link() ) : ?>
			<?php previous_posts_link( '<span>'.__('&laquo; Previous Events', 'tribe-events-calendar').'</span>' ); ?>
		<?php elseif( tribe_is_upcoming() && !get_previous_posts_link( ) ) : ?>
			<a href='<?php echo tribe_get_past_link(); ?>'><span><?php _e('&laquo; Previous Events', 'tribe-events-calendar' ); ?></span></a>
		<?php elseif( tribe_is_past() && get_next_posts_link( ) ) : ?>
			<?php next_posts_link( '<span>'.__('&laquo; Previous Events', 'tribe-events-calendar').'</span>' ); ?>
		<?php endif; ?>

		<?php
		// Display Next Page Navigation
		if( tribe_is_upcoming() && get_next_posts_link( ) ) : ?>
			<?php next_posts_link( '<span>'.__('Next Events &raquo;', 'tribe-events-calendar').'</span>' ); ?>
		<?php elseif( tribe_is_past() && get_previous_posts_link( ) ) : ?>
			<?php previous_posts_link( '<span>'.__('Next Events &raquo;', 'tribe-events-calendar').'</span>' ); // a little confusing but in 'past view' to see newer events you want the previous page ?>
		<?php elseif( tribe_is_past() && !get_previous_posts_link( ) ) : ?>
			<a href='<?php echo tribe_get_upcoming_link(); ?>'><span><?php _e('Next Events &raquo;', 'tribe-events-calendar'); ?></span></a>
		<?php endif; ?>

	</nav>

</section>
<?php get_sidebar(); ?>

