<?php
	/*
	Plugin Name: OAS Short URLs
	Plugin URI: http://wordpress.org/extend/plugins/oas-short-urls/
	Description: An extremely light-weight plugin which lets blog authors link to their pages and posts using ID's alone.
	Version: 0.1
	Author: Online Associates, UAE
	Author URI: http://www.onlineassociates.ae/
	*/

/*************************************************************************************
/									OAS Short URLs CLASS
/************************************************************************************/

if ( ! defined( 'WP_CONTENT_URL' ) ) define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) ) define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) ) define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

if ( !isset($G_OasShortUrls) )	$G_OasShortUrls = new OasShortUrls();
		
class OasShortUrls
{

	function OasShortUrls()
	{
		if ( version_compare($GLOBALS['wp_version'], '2.7', '>=') ) $this->ConfigInit();
	}
	
	function ConfigInit()
	{
		if( function_exists('add_action') ) $this->attachActions();
	}
	
	
	function attachActions()
	{
		add_action( 'template_redirect', array(&$this,'FollowRedirect') );
	}
	
	function FollowRedirect()
	{
		if( is_404() && function_exists('oas_get_post_exists') )
		{
			$Pattern = '/^\/([0-9]+)\/{0,1}$/';
			$BlogURL = strtolower($_SERVER['REQUEST_URI']);
			
			$BlogLocation = get_option( 'siteurl' );
			$SiteURL = parse_url($BlogLocation);
			$SitePath = $SiteURL['path'];
			
			$url = str_replace( $SitePath, '', $BlogURL );
	
			if( preg_match( $Pattern, $url, $matches ) )
			{
				$PostID = (int) $matches[1];
				if( !empty($PostID) && oas_get_post_exists($PostID) )
				{
					$RedirectTo = get_permalink($PostID);
					//header("Status: 301");
					header("Location: " . $RedirectTo, true, 301);
				}			
			}
		}	
		
	}
	
}				 	
	
?>