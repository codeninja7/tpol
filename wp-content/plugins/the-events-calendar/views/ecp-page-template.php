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
<?php tribe_events_before_html() ?>
<!-- h2 class="tribe-events-cal-title"><?php tribe_events_title(); ?></h2 -->
<header class="page-header">
    <h1 class="page-title align-left"><?php tribe_events_title(); ?></h1>
    <a href="/events" class="button no-bg medium align-right">
        All Events <img src="/wp-content/themes/smartstart/images/icon-grid.png" alt="" class="icon">
    </a>
</header>
<?php include(tribe_get_current_template()); ?>
<?php tribe_events_after_html() ?>
<?php get_footer(); ?>