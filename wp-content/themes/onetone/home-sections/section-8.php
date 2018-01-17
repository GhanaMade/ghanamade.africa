<?php
global $onetone_animated, $onetone_section_id, $allowedposttags;
 $i                   = $onetone_section_id ;
 $section_title       = onetone_option( 'section_title_'.$i );
 $section_menu        = onetone_option( 'menu_title_'.$i );
 $section_content     = onetone_option( 'section_content_'.$i ); 
 $content_model       = onetone_option( 'section_content_model_'.$i);
 $section_subtitle    = onetone_option( 'section_subtitle_'.$i );
 $btn_text            = onetone_option( 'section_btn_text_'.$i );
 $email               = onetone_option( 'section_email_'.$i );
 
 if( !isset($section_content) || $section_content=="" ) 
 	$section_content = onetone_option( 'sction_content_'.$i );

 if( $content_model == '0' || $content_model == ''  ):
?>
        
         <?php if( $section_title != '' || (function_exists('is_customize_preview') && is_customize_preview()) ):?>
       <?php  
		   $section_title_class = '';
		   if( $section_subtitle == '' && !(function_exists('is_customize_preview') && is_customize_preview()))
		   $section_title_class = 'no-subtitle';
		?>
       <h2 class="section-title <?php echo $section_title_class; ?> <?php echo 'section_title_'.$i;?>"><?php echo wp_kses($section_title, $allowedposttags);?></h2>
        <?php endif;?>
        <?php if( $section_subtitle != '' || (function_exists('is_customize_preview') && is_customize_preview()) ):?>
        <div class="section-subtitle <?php echo 'section_subtitle_'.$i;?>"><?php echo do_shortcode(wp_kses($section_subtitle, $allowedposttags));?></div>
         <?php endif;?>
         <div class="home-section-content">
         <div class="<?php echo $onetone_animated;?>" data-animationduration="0.9" data-animationtype="fadeIn" data-imageanimation="no">
         <div class="contact-area <?php echo 'section_btn_text_'.$i;?>">
<form class="contact-form" action="" method="post">
            <input id="name" tabindex="1" name="name" size="22" type="text" value="" placeholder="<?php _e('Name', 'onetone')?>" />
            <input id="email" tabindex="2" name="email" size="22" type="text" value="" placeholder="<?php _e('Email', 'onetone')?>" />
            <textarea id="message" tabindex="4" cols="39" name="x-message" rows="7" placeholder="<?php _e('Message', 'onetone')?>"></textarea>
            <input id="sendto" name="sendto" type="hidden" value="<?php echo $email;?>" />
            <input id="submit" name="submit" type="button" value="<?php echo $btn_text;?>" />
            </form>
            </div>
           </div>
          </div>
            <?php
		else:
		?>
        <?php if( $section_title != '' || (function_exists('is_customize_preview') && is_customize_preview()) ):?>
        <div class="section-title <?php echo 'section_title_'.$i;?>"><?php echo esc_attr($section_title);?></div>
        <?php endif;?>
           
            <div class="home-section-content <?php echo 'section_content_'.$i;?>">
            <?php 
			if(function_exists('Form_maker_fornt_end_main'))
             {
                 $section_content = Form_maker_fornt_end_main($section_content);
              }
			 echo do_shortcode(wp_kses($section_content, $allowedposttags));
			?>
            </div>
       <?php 
		endif;
		?>