<?php
/*
Template Name: Events
*/

get_header(); ?>

<?php $has_sidebar = ss_framework_check_page_layout(); ?>

<?php $page_title = ss_framework_get_custom_field('ss_page_title') ? ss_framework_get_custom_field('ss_page_title') : get_the_title(); ?>

<section id="content" class="clearfix <?php echo ss_framework_check_sidebar_position(); ?>">
    <div class="container">
        <header class="page-header">
<!--            <h1 class="page-title">--><?php //tribe_events_title(); ?><!--</h1>-->
            <h1 class="page-title">Upcoming Events</h1>
        </header>

<?php include(tribe_get_current_template()); ?>

    </div><!-- end .container -->

</section><!-- end #content -->

<?php
get_footer();