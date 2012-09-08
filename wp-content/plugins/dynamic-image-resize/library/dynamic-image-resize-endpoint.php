<?php
//Load Wordpress Short Init
define( 'SHORTINIT', true );
$basedir = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
require_once( $basedir . '/wp-load.php' );
require( ABSPATH . WPINC . '/formatting.php' );
wp_plugin_directory_constants();
require_once( 'config.php' );
wp_mkdir_p($cacheurl);
if ( isset($_GET['src']) ) {
	$_GET['src'] = preg_replace('!^wp-content/!', '', $_GET['src']);
}
$upload_dir = wp_upload_dir();
$defaults = array(
	'MAX_WIDTH' => 4000,
	'MAX_HEIGHT' => 4000,
	'FILE_CACHE_DIRECTORY' => $cacheurl,
	'MEMORY_LIMIT' => '1024M',
	'PNG_IS_TRANSPARENT' => true,
	'DEBUG_ON' => ( defined( 'IMG_DEBUG' ) ) ? IMG_DEBUG : false,
	'LOCAL_FILE_BASE_DIRECTORY' => dirname($upload_dir['basedir']),
);
foreach ( $defaults as $constant => $value ) {
	if ( !defined( $constant ) ) {
		define( $constant, $value );
	}
}
//$_GET['src'] = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REDIRECT_URL'];
//$_REQUEST['src'] = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REDIRECT_URL'];
include('timthumb.php');
?>