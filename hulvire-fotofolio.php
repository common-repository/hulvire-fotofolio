<?php
/*
Plugin Name: Hulvire fotofolio
Plugin URI: http://www.amfajnor.sk/_hulvire_web/hulvire%20old/index.htm
Description: 
Version: 1.5.0
Author: Fajnor
Author URI: http://amfajnor.sk
License: GPL2
Text Domain: mee
*/
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly. Meee';
	exit;
}

if(!class_exists('WP_Hulvire_Fotofolio'))
{
    class WP_Hulvire_Fotofolio
    {
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
			// Initialize Settings
			require_once(sprintf("%s/hulvire-fotofolio-settings.php", dirname(__FILE__)));
			$WP_Hulvire_Fotofolio_Settings = new WP_Hulvire_Fotofolio_Settings();
			
			// Register custom post types
			//require_once(sprintf("%s/post-types/hulvire-fotofolio-img_type.php", dirname(__FILE__)));
			//$Hulvire_Aktuality_Img_Type = new Hulvire_Aktuality_Img_Type();

			$plugin_fotofolio = plugin_basename(__FILE__);
			add_filter("plugin_action_links_$plugin_fotofolio", array( $this, 'plugin_settings_link' ));

			
			function huu_fotofolio_load_my_script() {
			    wp_enqueue_script( 'jquery' );
			}

			add_action( 'wp_enqueue_scripts', 'huu_fotofolio_load_my_script' );
			
			
			define( 'HUU_VERSION', '1.5' );
			define( 'HUU__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			define( 'HUU__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

			function huu_fotofolio_scripts_method() {
				wp_enqueue_style('hulvire-fotofolio-style_css', HUU__PLUGIN_URL .'css/hulvire-fotofolio-style.css');
				wp_enqueue_script('jquery.touchSwipe', HUU__PLUGIN_URL .'js/jquery.touchSwipe.min.js');
				wp_enqueue_script('hulvire-fotofolio-script', HUU__PLUGIN_URL .'js/hulvire-fotofolio-script.js');
			}
			add_action('wp_enqueue_scripts', 'huu_fotofolio_scripts_method');

			require_once('post-types/hulvire-fotofolio-text-type.php');
			
			
			function huu_fotofolio_script(){/*
				$settingA = get_option('setting_animacia');

				echo "<script type='text/javascript' charset='utf-8'>
				(function($) {
				    $(window).load(function() {
				        //$('.flexfotofolio').flexfotofolio({
					    //        animation: '$settingA',
						//    controlsContainer: '.flex-container'
					    });
				    });
				})(jQuery)
				</script>";
 			   */
			}
			add_action('wp_head', 'huu_fotofolio_script');

			function huu_get_fotofolio($atts){
				
				$a = shortcode_atts( array(
				        'id' => '4',
				    ), $atts );
				
					$ssetting_fotofolio_navigLABELS = get_option('setting_checkbox_navigLABELS');
					$ssetting_fotofolio_navigLABELS = isset($ssetting_fotofolio_navigLABELS) ? $ssetting_fotofolio_navigLABELS : "nie";
					
					$ssetting_fotofolio_showFOOTER = get_option('setting_checkbox_showFOOTER');
					$ssetting_fotofolio_showFOOTER = isset($ssetting_fotofolio_showFOOTER) ? $ssetting_fotofolio_showFOOTER : "nie";
					$ssetting_fotofolio_footer = get_option('setting_inputFooter');
					$ssetting_fotofolio_footer_color = get_option('setting_inputFooter_color');
				
				$ssetting_fotofolio_checkbox_popiskaPOSTU = get_option('setting_checkbox_popiskaPOSTU');
				$ssetting_fotofolio_checkbox_popiskaPOSTU = isset($ssetting_fotofolio_checkbox_popiskaPOSTU) ? $ssetting_fotofolio_checkbox_popiskaPOSTU : "nie";
				$ssetting_fotofolio_checkbox_popiskaIMG = get_option('setting_checkbox_popiskaIMG');
				$ssetting_fotofolio_checkbox_popiskaIMG = isset($ssetting_fotofolio_checkbox_popiskaIMG) ? $ssetting_fotofolio_checkbox_popiskaIMG : "nie";
				
				
				$fotofolio = "<div align='center'>";
				$fotofolio.= "<div class='fotofolio' title='{$a['id']}'>";
 
				    $huu_fotofolio_query = "post_type=hulvire_fotofolio&p={$a['id']}";
				    query_posts($huu_fotofolio_query);
     
     
				    if (have_posts()) : while (have_posts()) : the_post();
        						

							$values = get_post_custom( $post->ID );
							$idecko = get_the_ID();
		
							$fotofolio_before_text = isset( $values['fotofolio_before_text'] ) ? esc_attr( $values['fotofolio_before_text'][0] ) : '';
							$fotofolio_after_text = isset( $values['fotofolio_after_text'] ) ? esc_attr( $values['fotofolio_after_text'][0] ) : '';
						
							
								$fotofolio.="<ul class='slides' id='swipe_slides'>";
								
								$fotofolio.="<div class='navig'>";
								if($ssetting_fotofolio_checkbox_navigLABELS=="ano")
								{ $fotofolio.="<label class='prev'>previous</label><span> / </span><label class='next'>next</label>"; }
								//$fotofolio.="<div class='navig-f-prev'></div><div class='navig-f-next'></div>";
								$fotofolio.="<div class='navig-f-swipe'></div>";
								$fotofolio.="</div>";
								
								$sk = 1;
								
								$images = rwmb_meta( 'hulvire_fotofolio_imgadv', 'type=image' );
								foreach ( $images as $image )
								{
									$kolko = count($images);
									$skprev = $sk-1;
									$sknext = $sk+1;
									if($sk==1) { $ischecked = "active"; }else{ $ischecked = ""; }
									
									//$fotofolio.='<i>'.print_r($images).'</i>';
																		
									$fotofolio.="<li class='slide-container'>";
									
									$fotofolio.="<a class='slide'>";
									
									$fotofolio.="<img class='{$ischecked}' id='{$sk}' src='{$image['full_url']}' alt='{$image['alt']}' />";
								
									$fotofolio.="</a>";	
									
									if($ssetting_fotofolio_checkbox_popiskaIMG=="ano")
									{ $fotofolio.="<p class='image-popiska {$ischecked}'>{$image['caption']}</p>"; }								
									
									$fotofolio.="</li>";
									
									$sk++;
								}
								
								$fotofolio.="</ul><!--/.slides-->";

								
								$fotofolio.="<div class='folioContent'>";
								$fotofolio.="<p><span class='kolky'>1</span> / <span class='kolko'>{$kolko}</span></p>";
								
								
								if ($ssetting_fotofolio_checkbox_popiskaPOSTU == "ano"){
									$fotofolio.="<div class='before-more'>";
								
									if ($fotofolio_before_text!="")
									$fotofolio.="<p>{$fotofolio_before_text}</p>";
								
									$fotofolio.="</div><!--/div before-more-->";
								
									$fotofolio.="<div id='aftermore{$idecko}' style='display: none;' class='aftermore'>";
									$fotofolio.="<p><span id='more{$idecko}'></span>";
								
									if ($fotofolio_after_text!="")
									$fotofolio.= $fotofolio_after_text."</p>";
								
									$fotofolio.="</div><!--/div aftermore-->";
								
									$javascriptCall = "javascript:toggleDiv('aftermore{$idecko}','toggler{$idecko}',{$idecko});";
									$fotofolio.="<p class='textRight'><a href='{$javascriptCall} id='toggler{$idecko}'>Zobraz viac</a></p>";
								}
								
								if ($ssetting_fotofolio_showFOOTER == "ano"){
									$fotofolio.="<div class='folio-footer' style='color:{$ssetting_fotofolio_footer_color}'>{$ssetting_fotofolio_footer}</div>";
								}
								
								$fotofolio.="</div><!--/div folioContent-->";
							
							
						
             
				    endwhile; endif; 
		
					wp_reset_query();
 
				    $fotofolio.= '</div><!--/div fotofolio-->';
					$fotofolio.= '</div><!--/div center-->';
					
     
				    return $fotofolio; 
			}
 
			/**add the shortcode for the fotofolio- for use in editor**/
			function huu_insert_fotofolio($atts, $content = null){
 
				$fotofolio = huu_get_fotofolio($atts);
 
				return $fotofolio;
			}
			add_shortcode('huu_fotofolio', 'huu_insert_fotofolio');
 
			/**add template tag- for use in themes**/
			function huu_fotofolio($atts){
	
			    echo huu_get_fotofolio($atts);
			}

			
        } // END public function __construct

        /**
         * Activate the plugin
         */
        public static function activate()
        {
            // Do nothing
        } // END public static function activate

        /**
         * Deactivate the plugin
         */     
        public static function deactivate()
        {
            // Do nothing
        } // END public static function deactivate
		
		// Add the settings link to the plugins page
		function plugin_settings_link($links)
		{
			$settings_link = '<a href="options-general.php?page=wp_hulvire_fotofolio">Settings</a>';
			array_unshift($links, $settings_link);
			return $links;
		}
		
		
    } // END class WP_Hulvire_Fotofolio
} // END if(!class_exists('WP_Hulvire_Fotofolio'))

if(class_exists('WP_Hulvire_Fotofolio'))
{
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('WP_Hulvire_Fotofolio', 'activate'));
    register_deactivation_hook(__FILE__, array('WP_Hulvire_Fotofolio', 'deactivate'));

    // instantiate the plugin class
    $wp_hulvire_fotofolio = new WP_Hulvire_Fotofolio();
}
