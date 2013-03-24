<?php
/**
* A single event.  This displays the event title, description, meta, and 
* optionally, the Google map for the event.
*
* You can customize this view by putting a replacement file of the same name (single.php) in the events/ directory of your theme.
*/

// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }
?>

<?php
	$gmt_offset = (get_option('gmt_offset') >= '0' ) ? ' +' . get_option('gmt_offset') : " " . get_option('gmt_offset');
 	$gmt_offset = str_replace( array( '.25', '.5', '.75' ), array( ':15', ':30', ':45' ), $gmt_offset );
?>
<div class="entry-meta" itemprop="location" itemscope itemtype="http://schema.org/Place">
	<ul>
		<li>
			<span class="title"><?php _e('Event:', 'tribe-events-calendar') ?></span>
			<span><?php the_title() ?></span>
		</li>
		<?php if (tribe_get_start_date() !== tribe_get_end_date() ) { ?>
			<li>
				<span class="title"><?php _e('Start:', 'tribe-events-calendar') ?></span>
				<time itemprop="startDate" content="<?php echo tribe_get_start_date(); ?>"><?php echo tribe_get_start_date(null,true,'M j, Y'); ?></time>
			</li>
			<li>
				<span class="title"><?php _e('End:', 'tribe-events-calendar') ?></span>
				<time itemprop="endDate" content="<?php echo tribe_get_end_date(); ?>"><?php echo tribe_get_end_date(null,true,'M j, Y'); ?></time>
			</li>
		<?php } else { ?>
			<li>
				<span class="title"><?php _e('Date:', 'tribe-events-calendar') ?></span>
				<time itemprop="startDate" content="<?php echo tribe_get_start_date( null, false, 'Y-m-d-h:i:s' ); ?>"/><?php echo tribe_get_start_date(null,true,'M j, Y'); ?></time>
			</li>
		<?php } ?>
		<?php if ( tribe_get_cost() ) : ?>
			<li>
				<span class="title"><?php _e('Cost:', 'tribe-events-calendar') ?></span>
				<span itemprop="price"><?php echo tribe_get_cost(); ?></span>
			</li>
		<?php endif; ?>
		<?php //tribe_meta_event_cats(); ?>
		<?php if ( tribe_get_organizer_link( get_the_ID(), false, false ) ) : ?>
			<li>
				<span class="title"><?php _e('Organizer:', 'tribe-events-calendar') ?></span>
				<?php echo tribe_get_organizer_link(); ?>
			</li>
			<?php elseif (tribe_get_organizer()): ?>
			<li>
				<span class="title"><?php _e('Organizer:', 'tribe-events-calendar') ?></span>
				<span><?php echo tribe_get_organizer(); ?></span>
			</li>
		<?php endif; ?>
		<?php if ( tribe_get_organizer_phone() ) : ?>
			<li>
				<span class="title"><?php _e('Phone:', 'tribe-events-calendar') ?></span>
				<span><?php echo tribe_get_organizer_phone(); ?></span>
			</li>
		<?php endif; ?>
		<?php if ( tribe_get_organizer_email() ) : ?>
			<li>
				<span class="title"><?php _e('Email:', 'tribe-events-calendar') ?></span>
				<a href="mailto:<?php echo tribe_get_organizer_email(); ?>"><?php echo tribe_get_organizer_email(); ?></a>
			</li>
		<?php endif; ?>
		<li>
			<span class="title"><?php _e('Updated:', 'tribe-events-calendar') ?></span>
			<span><?php the_date(); ?></span>
		</li>
		<?php if ( class_exists('TribeEventsRecurrenceMeta') && function_exists('tribe_get_recurrence_text') && tribe_is_recurring_event() ) : ?>
			<li>
				<span class="title"><?php _e('Schedule:', 'tribe-events-calendar') ?></span>
				<span><?php echo tribe_get_recurrence_text(); ?></span>
				<?php if( class_exists('TribeEventsRecurrenceMeta') && function_exists('tribe_all_occurences_link')): ?>(<a href='<?php tribe_all_occurences_link() ?>'>See all</a>)<?php endif; ?>
			</li>
		<?php endif; ?>
	</ul>
	<ul>
		<?php if(tribe_get_venue()) : ?>
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
		<?php if(tribe_get_phone()) : ?>
			<li>
				<span class="title"><?php _e('Phone:', 'tribe-events-calendar') ?></span>
				<span itemprop="telephone"><?php echo $phone; ?></span>
			</li>
		<?php endif; ?>
		<?php if( tribe_address_exists( get_the_ID() ) ) : ?>
			<li>
				<span class="title"><?php _e('Address:', 'tribe-events-calendar'); ?></span>
				<span>
					<?php echo tribe_get_full_address( get_the_ID() ); ?>
					<?php if( tribe_show_google_map_link( get_the_ID() ) ) : ?>
					<br/><a class="gmap" itemprop="maps" href="<?php echo tribe_get_map_link(); ?>" title="Click to view a Google Map" target="_blank"><?php _e('Google Map', 'tribe-events-calendar' ); ?></a>
					<?php endif; ?>
				</span>
			</li>
		<?php endif; ?>
	</ul>
</div>


<?php if( tribe_embed_google_map( get_the_ID() ) ) : ?>
<?php if( tribe_address_exists( get_the_ID() ) ) { echo tribe_get_embedded_map(); } ?>
<?php endif; ?>
<div class="entry-body">
	<?php
	$thumbnail_size = isset( $GLOBALS['post-carousel'] ) ? 'blog-post-thumb' : 'blog-post';
	if( has_post_thumbnail() ): ?>

	<?php $post_thumbnail_img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), apply_filters('ss_content_thumbnail_size', $thumbnail_size, $post) ); ?>
	<?php $post_thumbnail_data = ss_framework_get_the_post_thumbnail_data( $post->ID ); ?>

	<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
		<img src="<?php echo $post_thumbnail_img[0]; ?>" alt="<?php echo $post_thumbnail_data['alt']; ?>" title="<?php echo $post_thumbnail_data['title']; ?>" class="entry-image <?php echo $post_thumbnail_data['class']; ?>">
	</a>

	<?php endif; ?>
	<?php if( !is_single() ): ?>
		<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__('Permalink to %s', 'ss_framework'), the_title_attribute('echo=0') ); ?>" rel="bookmark">
			<h1 class="title"><?php the_title(); ?></h1>
		</a>
	<?php endif; ?>
	<?php echo ss_framework_post_content(); ?>
	<?php if (function_exists('tribe_get_ticket_form') && tribe_get_ticket_form()) { tribe_get_ticket_form(); } ?>		
</div>
<?php if( function_exists('tribe_get_single_ical_link') ): ?>
   <a class="ical single" href="<?php echo tribe_get_single_ical_link(); ?>"><?php _e('iCal Import', 'tribe-events-calendar'); ?></a>
<?php endif; ?>
<?php if( function_exists('tribe_get_gcal_link') ): ?>
   <a href="<?php echo tribe_get_gcal_link() ?>" class="gcal-add" title="<?php _e('Add to Google Calendar', 'tribe-events-calendar'); ?>"><?php _e('+ Google Calendar', 'tribe-events-calendar'); ?></a>
<?php endif; ?>