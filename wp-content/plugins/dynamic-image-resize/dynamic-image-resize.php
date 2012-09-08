<?php
/**
 * @package Dynamic Image Resizer
 * @version 1.0
 */
/*
Plugin Name: Dynamic Image Resizer
Description: Dynamically resize images based using timthumb. *** IMPORTANT / WARNING *** When activating and deactivating plugin, the .htaccess file needs to be manually updated on production servers.  Production servers are set so that the .htacess file can only be written by "root" access.
Author: Modern Tribe, Inc.
Version: 1.0
Author URI: http://tri.be
*/

/*
Need to set the caching location and policies
Need to handle security / restric access from other urls
Rewrite urls to hide timthumb.
Integrate with CDN
*/

if ( !class_exists( 'DynamicImageResizer' ) ) {
	class DynamicImageResizer {

		public $pluginDir;
		public $pluginPath;
		public $pluginUrl;
		public $pluginName;
		public $pluginSlug = 'dynamic-image-resizer';
		public $pluginDomain = 'dynamic-image-resizer';
		public $defaults = array(
			'quality' => 89,
			'align' => 't',
			'sharpen' => 0,
		);
		public $imageCacheDir;

		public function __construct() {
			$this->pluginName = __( 'Dynamic Image Resizer', $this->pluginDomain );
			$this->pluginDir = trailingslashit( basename( dirname(__FILE__) ) );
			$this->pluginPath = trailingslashit( dirname(__FILE__) );

			require_once('library/config.php');
			$this->imageCacheDir = $cacheurl;

			$this->addActions();
			$this->addFilters();
            register_activation_hook(__FILE__, array($this, 'doActivation'));
            register_deactivation_hook(__FILE__, array($this, 'doDeactivation'));
		}

		private function addActions() {
			add_action('post-flash-upload-ui',array($this,'declare_max_w_h_flash_media_upload'));
		}

		private function addFilters() {
			add_filter('image_downsize', array($this,'filter_image_downsize'), 10, 3);
			add_filter('intermediate_image_sizes_advanced', array($this,'filter_intermediate_image_sizes_advanced'));
		}
		
		public function filter_image_downsize( $bool, $id, $size ) {
			global $_wp_additional_image_sizes;
			if ( is_array($size) || 'full' == $size ) {
				return;
			}
			if (is_array($_wp_additional_image_sizes) && (is_array($size) || array_key_exists($size,$_wp_additional_image_sizes))) {
				
				// noting this incase we want to use this and subtract the domain out of it.
				$src = wp_get_attachment_image_src( $id, 'full' );
				$original_width = $src[1];
				$original_height = $src[2];
				$src = $src[0];

				if ( is_array($size) ) {
					$w = $size[0];
					$h = $size[1];
					$crop = 1;
				} else {
					$w = $_wp_additional_image_sizes[$size]['width'];
					$h = $_wp_additional_image_sizes[$size]['height'];
					$crop = (int)$_wp_additional_image_sizes[$size]['crop'];
				}
				if ( !$crop ) { $crop = 3; }

				if ( $crop != 1 && $original_height <= $h && $original_width <= $w ) {
					return array( $src, $original_width, $original_height, false );
				}

				$src = $this->urlencode($src);

				$url = $src .
					'?w=' . $w . 
					'&h=' . $h . 
					'&zc=' . $crop . 
					'&s=' . $this->defaults['sharpen'] . 
					'&a=' . $this->defaults['align'] .
					'&q=' . $this->defaults['quality'];
				// $vars = array(
				//	'id' => $id,
				//	'size' => $size,
				//	'size_details' => $_wp_additional_image_sizes[$size],
				//	'url' => $url,
				// );
				//error_log( 'filter_image_downsize: ' . print_r( $vars, true ) );
				do_action('log','filter_image_downsize','images',array(
					'$id' => $id,
					'$size' => $size,
					'$url' => $url,
					'$w' => $w,
					'$h' => $h,
				));

				// if we're not cropping, we need to know the correct size of the image
				if ( $crop != 1 ) {
					list($w, $h) = wp_constrain_dimensions($original_width, $original_height, $w, $h);
				}
				return array( $url, $w, $h, true );
			}
		}
		
		public function filter_intermediate_image_sizes_advanced( $sizes ) {
			$defaultsArray = array(
				'thumbnail' => array(
					'width' => $sizes['thumbnail']['width'],
					'height' => $sizes['thumbnail']['height'],
					'crop' => $sizes['thumbnail']['crop'],
				),
				'medium' => array(
					'width' => $sizes['medium']['width'],
					'height' => $sizes['medium']['height'],
					'crop' => $sizes['medium']['crop'],
				),
				'large' => array(
					'width' => 630,
					'height' => 630,
					'crop' => $sizes['large']['crop'],
				),
			);
			return $defaultsArray;
		}

        public function doActivation() {
            if (function_exists('is_wpmu_sitewide_plugin')) {
                $this->saveModRewriteRules();
            } else {
                global $wp_rewrite;
                $wp_rewrite->flush_rules(true);
            }
            
            if (!file_exists($this->imageCacheDir)) {
                wp_mkdir_p($this->imageCacheDir);
            }
        }

        public function doDeactivation() {
            if (function_exists('is_wpmu_sitewide_plugin')) {
                $this->removeModRewriteRules();
            } else {
                global $wp_rewrite;
                $wp_rewrite->flush_rules(true);
            }
            
            if (file_exists($this->imageCacheDir)) {
            	$this->removeTarget($this->imageCacheDir);
            }
        }
        
        public function removeTarget($target){
        	error_log('target:'.$target);
        	if (file_exists($target)) {
                rmdir($target);
            }
        }

        private function getCustomRules() {
            return file_get_contents(dirname(__FILE__).'/library/1.htaccess');
        }

		private function saveModRewriteRules() {
            $htaccessContents = $this->getHtaccessContents();
            
            if ($htaccessContents && false === strpos($htaccessContents, '#AUTORESIZER#')) {
                $customRules = $this->getCustomRules();
                $htaccessContents = preg_replace('/(RewriteBase.*)/', '$1'.str_replace('$', '\$', "\n\n".$customRules), $htaccessContents);
                $this->writeHtaccessContents($htaccessContents);
            }
        }
        
        private function removeModRewriteRules() {
            $htaccessContents = $this->getHtaccessContents();
            
            if ($htaccessContents && false !== strpos($htaccessContents, '#AUTORESIZER#')) {
                $start = strpos($htaccessContents, '#AUTORESIZER#');
                $end = strpos($htaccessContents, '#ENDAUTORESIZER#') + strlen('#ENDAUTORESIZER#');
                
                $htaccessContents = substr_replace($htaccessContents, '', $start-2, $end - $start + 2);
                $this->writeHtaccessContents($htaccessContents);
            }
        }
        
        private function getHtaccessContents() {
            $homePath = get_home_path();
            $htaccessFile = $homePath.'.htaccess';
            if (file_exists($htaccessFile) && is_writable($htaccessFile)) {
                return file_get_contents($htaccessFile);
            } else {
                return false;
            }
        }
        
        private function writeHtaccessContents($contents) {
            $homePath = get_home_path();
            $htaccessFile = $homePath.'.htaccess';
            if (file_exists($htaccessFile) && is_writable($htaccessFile)) {
                $htaccessHandle = fopen($htaccessFile, 'w');
                fwrite($htaccessHandle, $contents);
            } else {
                return false;
            }
        }
        
        public function declare_max_w_h_flash_media_upload(){
        	echo '<p class="media-upload-size">Maximum width and height of images: 2400px by 2400px (required for dynamic image resizing)</p>';
        }

		/**
		 * Double-urlencode the filename portion of a URL
		 *
		 * A string like "%20" should end up like "%252520"
		 *
		 * @param string $url
		 * @return string
		 */
		private function urlencode( $url ) {
			$basename = wp_basename($url);
			$encoded = urlencode(urlencode($basename));
			$url = str_replace($basename, $encoded, $url);
			return $url;
		}

	}
	
	global $dynamicimageresizer;
	$dynamicimageresizer = new DynamicImageResizer();
	include('library/template-tags.php');
}
?>