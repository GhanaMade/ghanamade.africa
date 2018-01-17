<?php
/*
	Plugin Name: OneTone Companion
	Description: OneTone theme options.
	Author: MageeWP
	Author URI: https://www.mageewp.com/
	Version: 1.0.2
	Text Domain: onetone-companion
	Domain Path: /languages
	License: GPL v2 or later
*/

if ( !defined('ABSPATH') ) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

if(!class_exists('OnetoneCompanion')){
	
	class OnetoneCompanion{
	
		public function __construct() {
	
			register_activation_hook( __FILE__, array(&$this ,'plugin_activate') );
			add_action( 'plugins_loaded', array( $this, 'init' ) );
			add_action('admin_menu', array(&$this,'create_menu'));
			add_action( 'admin_enqueue_scripts',  array($this,'admin_scripts' ));
			add_action('switch_theme', array(&$this ,'plugin_activate'));
		}
		
		
		function plugin_activate( $network_wide ) {
			
			$my_theme = wp_get_theme();
			$theme = $my_theme->get( 'Name' );
			
			if( !$theme == 'Onetone' && !$theme == 'Onetone Pro' )
				return;
				
			$homepage_title = 'Onetone Front Page';
			$posts_page_title='Onetone Blog';
			// Set reading options
			$homepage   = get_page_by_title( $homepage_title );
			$posts_page = get_page_by_title( $posts_page_title );
			
			
			if( !isset($homepage->ID) || (isset($homepage->post_status) && $homepage->post_status 
			!= 'publish' ) ){
				
				$post_data = array(
					  'post_title' => $homepage_title,
					  'post_content' => '',
					  'post_status'   => 'publish',
					  'post_type' => 'page',
				  );  
				$homepage_id = wp_insert_post( $post_data );
				
			}else{
				
				$homepage_id = $homepage->ID;
				}
				
				
			if( !isset($posts_page->ID) || (isset($posts_page->post_status) && $posts_page->post_status 
			!= 'publish')){
				
				$post_data = array(
					  'post_title' => $posts_page_title,
					  'post_content' => '',
					  'post_status'   => 'publish',
					  'post_type' => 'page',
				  );  
				$posts_page_id = wp_insert_post( $post_data );
				
			}else{
				
				$posts_page_id = $posts_page->ID;
				}
			
			if( $homepage_id && $posts_page_id ) {
				//if(!get_option('page_on_front')){
					update_option('show_on_front', 'page');
					update_option('page_on_front', $homepage_id); // Front Page
					update_post_meta( $homepage_id, '_wp_page_template', 'template-home.php' );
				//}
				//if(!get_option('page_for_posts')){
					update_option('page_for_posts', $posts_page_id); // Blog Page
					
				//}
			}
					
			}
			
			
		public static function init() {
			load_plugin_textdomain( 'onetone-companion', false,  basename( dirname( __FILE__ ) ) . '/languages' );
		}
		
		function admin_scripts() {
	    	//wp_enqueue_style( 'wp-color-picker' );
        	//wp_enqueue_script( 'onetone-companion-admin-js',  plugins_url( 'assets/js/admin.js',__FILE__ ), array( 'jquery','wp-color-picker' ),'', true );
			if(isset($_GET['page']) && $_GET['page']=='onetone-companion/onetone-companion.php' )
				wp_enqueue_style( 'onetone-companion-admin-css',  plugins_url( 'assets/css/admin.css',__FILE__ ), '','', false );
		}
		
		function create_menu() {
		
			//create new top-level menu
			add_menu_page( __('OneTone Companion','onetone-companion'), __('OneTone Companion','onetone-companion'), 'administrator', __FILE__, array(&$this,'settings_page'),'dashicons-admin-generic');
		
			//call register settings function
			add_action( 'admin_init', array(&$this,'register_mysettings') );
		}
		
		
	public static function default_options(){

			$return = array(
				'onetone_homepage_sections' => '',
				'onetone_homepage_options' => '',
				'onetone_slideshow' => '',
				'onetone_general_option'  => '',
				'onetone_header' => '',
				'onetone_page_title_bar' => '',
				'onetone_styling' => '',
				'onetone_sidebar' =>'',
				'onetone_footer' => '',

			);
			
			return $return;
			
			}
			
		function text_validate($input)
		{
			
			$default_options = array(
				'onetone_homepage_sections' => '',
				'onetone_homepage_options' => '',
				'onetone_slideshow' => '',
				'onetone_general_option'  => '',
				'onetone_header' => '',
				'onetone_page_title_bar' => '',
				'onetone_styling' => '',
				'onetone_sidebar' =>'',
				'onetone_footer' => '',

			);
			$input = wp_parse_args($input,$default_options);
			
			$input['onetone_homepage_sections'] = sanitize_text_field($input['onetone_homepage_sections']);
			$input['onetone_homepage_options'] = sanitize_text_field($input['onetone_homepage_options']);
			$input['onetone_slideshow'] = sanitize_text_field($input['onetone_slideshow']);
			$input['onetone_general_option'] = sanitize_text_field($input['onetone_general_option']);
			$input['onetone_header'] = sanitize_text_field($input['onetone_header']);
			$input['onetone_page_title_bar'] = sanitize_text_field($input['onetone_page_title_bar']);
			$input['onetone_styling'] = sanitize_text_field($input['onetone_styling']);
			$input['onetone_sidebar'] = sanitize_text_field($input['onetone_sidebar']);
			$input['onetone_footer'] = sanitize_text_field($input['onetone_footer']);
			
			return $input;
		}
		
		function register_mysettings() {
			//register settings
			register_setting( 'onetone-settings-group', 'onetone_companion_options', array(&$this,'text_validate') );
		}
		
		function settings_page() {
			
			$tabs = array('customizer-sections'   => esc_html__( 'Customizer Panels', 'onetone-companion' ),);
	$current = 'customizer-sections';
	if(isset($_GET['tab']))
		$current = $_GET['tab'];
		
		$html = '<h2 class="nav-tab-wrapper">';
		foreach( $tabs as $tab => $name ){
			$class = ( $tab == $current ) ? 'nav-tab-active' : '';
			$html .= '<a class="nav-tab ' . $class . '" href="?page=onetone-companion/onetone-companion.php&tab=' . $tab . '">' . $name . '</a>';
		}
		$html .= '</h2>';
		
			?>
			<div class="wrap">
			<?php echo $html;?>
			
			<form method="post" action="options.php">
				<?php
				
				settings_fields( 'onetone-settings-group' );
				$options     = get_option('onetone_companion_options',OnetoneCompanion::default_options());
				$onetone_companion_options = wp_parse_args($options,OnetoneCompanion::default_options());
				?>
                
                <div class="oc-customizer-sections">
                <div class="oc-description"><?php _e('Disable the customizer panels that you do not have or need anymore to load it quickly.Your settings are saved, so do not worry.','onetone-companion');?></div>
  <div class="row">
    <span><?php _e('Home Page Sections','onetone-companion');?> <input name="onetone_companion_options[onetone_homepage_sections]" type="checkbox"  <?php if($onetone_companion_options['onetone_homepage_sections']==1 ){ ?>checked="checked"<?php }?> value="1" /></span>
    <span><?php _e('Home Page Options','onetone-companion');?> <input name="onetone_companion_options[onetone_homepage_options]" type="checkbox"  <?php if($onetone_companion_options['onetone_homepage_options']==1 ){ ?>checked="checked"<?php }?> value="1" /></span>
    <span><?php _e('Slideshow','onetone-companion');?> <input name="onetone_companion_options[onetone_slideshow]" type="checkbox"  <?php if($onetone_companion_options['onetone_slideshow']==1 ){ ?>checked="checked"<?php }?> value="1" /></span>
  </div>
  
 <div class="row">
    <span><?php _e('General Opions','onetone-companion');?> <input name="onetone_companion_options[onetone_general_option]" type="checkbox"  <?php if($onetone_companion_options['onetone_general_option']==1 ){ ?>checked="checked"<?php }?> value="1" /></span>
    <span><?php _e('Header','onetone-companion');?> <input name="onetone_companion_options[onetone_header]" type="checkbox"  <?php if($onetone_companion_options['onetone_header']==1 ){ ?>checked="checked"<?php }?> value="1" /></span>
    <span><?php _e('Page Title Bar','onetone-companion');?> <input name="onetone_companion_options[onetone_page_title_bar]" type="checkbox"  <?php if($onetone_companion_options['onetone_page_title_bar']==1 ){ ?>checked="checked"<?php }?> value="1" /></span>
  </div>
  
  <div class="row">
    <span><?php _e('Styling','onetone-companion');?> <input name="onetone_companion_options[onetone_styling]" type="checkbox"  <?php if($onetone_companion_options['onetone_styling']==1 ){ ?>checked="checked"<?php }?> value="1" /></span>
    <span><?php _e('Sidebar','onetone-companion');?> <input name="onetone_companion_options[onetone_sidebar]" type="checkbox"  <?php if($onetone_companion_options['onetone_sidebar']==1 ){ ?>checked="checked"<?php }?> value="1" /></span>
    <span><?php _e('Footer','onetone-companion');?> <input name="onetone_companion_options[onetone_footer]" type="checkbox"  <?php if($onetone_companion_options['onetone_footer']==1 ){ ?>checked="checked"<?php }?> value="1" /></span>
  </div>

	<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes','onetone-companion') ?>" />
				</p>			
</div>	
				
			
			</form>
			</div>
		<?php 
		
		}
	  
	  }
	
}

new OnetoneCompanion();