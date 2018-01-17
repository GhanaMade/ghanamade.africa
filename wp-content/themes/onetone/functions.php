<?php

/**
 * Theme Functions
 **/
require_once dirname( __FILE__ ) . '/lib/kirki/kirki.php';

// customizer controls
require_once dirname( __FILE__ ) . '/lib/customizer-controls/editor/editor-control.php';
require_once dirname( __FILE__ ) . '/lib/customizer-controls/editor/editor-page.php';

load_template( trailingslashit( get_template_directory() ) . 'includes/theme-functions.php' );

global $onetone_options_saved, $onetone_old_version, $onetone_option_name, $onetone_model_v;
$onetone_options_saved = false;
$onetone_old_version   = false;
$onetone_model_v       = false;
$onetone_option_name   = onetone_option_name();

if ( $theme_options = get_option($onetone_option_name) ) {
	
$onetone_options_saved = true;
if( (isset($theme_options['section_content_0']) && $theme_options['section_content_0'] != '') &&
	(isset($theme_options['section_content_1']) && $theme_options['section_content_0'] != '') &&
	(isset($theme_options['section_content_2']) && $theme_options['section_content_0'] != '') ){
	
	$onetone_old_version = true;
}
if( isset($theme_options['section_content_model_0']) ||
	isset($theme_options['section_content_model_1']) ||
	isset($theme_options['section_content_model_2']) ||
	isset($theme_options['section_content_model_3']) ){
	$onetone_model_v = true;
}

// Version <= 2.0.5
}


/**
 * Mobile Detect Library
 **/
if(!class_exists("Mobile_Detect")){
	load_template( trailingslashit( get_template_directory() ) . 'includes/Mobile_Detect.php' );
 }
/**
 * Theme setup
 **/
 
load_template( trailingslashit( get_template_directory() ) . 'includes/theme-setup.php' );

require_once dirname( __FILE__ ) . '/lib/kirki/options.php';

/**
 * Theme breadcrumb
 */
load_template( trailingslashit( get_template_directory() ) . 'includes/breadcrumbs.php');

/**
 * Theme widget
 **/
load_template( trailingslashit( get_template_directory() ) . 'includes/theme-widget.php' );

/**
 * Meta box
 **/
 
load_template( trailingslashit( get_template_directory() ) . 'includes/metabox-options.php' );



/**
 * Framework Config Filter
 */
function onetone_framework_config_filter( $config) {
    $config['url_path'] = get_template_directory_uri().'/lib/kirki/';
    return $config;
}
add_filter( 'options-framework/config', 'onetone_framework_config_filter', 10, 3 );

/**
 * Include the TGM_Plugin_Activation class.
 */
load_template( trailingslashit( get_template_directory() ) . 'includes/class-tgm-plugin-activation.php' );


add_action( 'tgmpa_register', 'onetone_theme_register_required_plugins' );

/**
 * Register the required plugins for this theme.
 *
 */
function onetone_theme_register_required_plugins() {

    $plugins = array(
		array(
			'name'     				=> __('Onetone Companion','onetone'), // The plugin name
			'slug'     				=> 'onetone-companion', // The plugin slug (typically the folder name)
			'source'   				=> esc_url('https://downloads.wordpress.org/plugin/onetone-companion.zip'), // The plugin source
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '1.0.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),
		
	);

    /**
     * Array of configuration settings. Amend each line as needed.
     */
    $config = array(
        'id'           => 'onetone-companion',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to pre-packaged plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
      
    );

    tgmpa( $plugins, $config );

}