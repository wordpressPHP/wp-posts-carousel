<?php
/*
Author: Marcin Gierada
Author URI: http://www.teastudio.pl/
Author Email: m.gierada@teastudio.pl
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

class WpPostsCarouselGenerator {
    
        public static function generateId() {
                return rand();
        }
    
        public static function getDefaults() {
                return array(
                            'id'                      => self::generateId(),
                            'template'                => 'default.css',
                            'post_type'               => 'post',
                            'ordering'                => 'asc',
                            'categories'              => '',
                            'all_items'               => 10,

                            'show_title'              => 'true',
                            'show_created_date'       => 'true',
                            'show_description'        => 'true',
                            'show_category'           => 'true',
                            'show_tags'               => 'false',
                            'show_more_button'        => 'true',    
                            'show_featured_image'     => 'true',
                            'image_source'            => 'thumbnail',            
                            'image_width'             => 100,
                            'image_height'            => 100,  

                            'items_to_show'           => 4,
                            'loop'                    => 'true',
                            'auto_play'               => 'true',
                            'stop_on_hover'           => 'true',
                            'auto_play_timeout'       => 1200,
                            'nav'                     => 'true',
                            'nav_speed'               => 800,  
                            'dots'                    => 'true',
                            'dots_speed'              => 800,
                            'lazy_load'               => 'false',
                            'mouse_drag'              => 'true',
                            'mouse_wheel'             => 'true',
                            'touch_drag'              => 'true',
                            'slide_by'                => 1,
                            'easing'                  => "linear"
                           );        
        }
    
    
        public static function generate($atts) {
                global $post;
                
                
                /*
                 * default parameters
                 */
                $params = self::prepareSettings($atts);

                /*
                 * post type
                 */
                $post_type = $params['post_type'] ? $params['post_type'] : 'post';
                $post_type_category = $params['post_type'].'_category';  
                $post_tag = $params['post_type'].'_tag';  
                
                if ($post_type === 'post') {
                    $post_type_category = 'category';
                }
                        
                /*
                 * print styles
                 */
                wp_print_scripts('jquery-owl.carousel');
                wp_print_styles('owl.carousel.style');
        
                /*
                 * theme
                 */
                $theme =  $params['template'];
                $theme_name = str_replace('.css', '', $theme);

                /*
                 * check if template css file exists
                 */
                $plugin_theme_url = plugins_url( dirname(plugin_basename(__FILE__)) ) . '/templates/' . $theme;
                $plugin_theme_file = plugin_dir_path( __FILE__ ) . '/templates/'. $theme;
                
                $site_theme_url = get_template_directory_uri() . '/css/wp-posts-carousel/' . $theme;
                $site_theme_file = get_template_directory( __FILE__ ) . '/css/wp-posts-carousel/'. $theme;                

                if ( @file_exists($plugin_theme_file) ) {
                        wp_enqueue_style( 'wp_posts_carousel-carousel-style-'. $theme_name, $plugin_theme_url, true );
                } else if ( @file_exists($site_theme_file) ) {
                        wp_enqueue_style( 'wp_posts_carousel-carousel-style-'. $theme_name, $site_theme_url, true );                        
                } else {
                    return '<div class="error"><p>'. sprintf( __('Theme - %s.css stylesheet is missing.', 'wp-posts-carousel'), $theme ) .'</p></div>'; 
                }        
        
                /*
                 * prepare html and loop
                 */
                $out = '<div id="wp-posts-carousel-'. $params['id'] .'" class="'. $theme_name .'-theme wp-posts-carousel owl-carousel">';

                /*
                 * prepare sql query
                 */
                $sql_array = array('post_type'      =>  $post_type,               
                                   'post_status'    =>  'publish',
                                   'order'          =>  $params['ordering'],
                                   'posts_per_page' =>  $params['all_items'],
                                   'no_found_rows'  =>  1,
                                   //'post__not_in' =>  array($post->ID) //exclude current post
                                   );

                if ($params['categories'] != "") {
                        $sql_array['tax_query'] = array(array('taxonomy'  =>  $post_type_category,
                                                              'field'     =>  'id',
                                                              'terms'     =>  explode(',', $params['categories']),
                                                              'operator'  => 'IN'
                                                             ));
                }

                $sql_array['orderby'] = 'date';    
                
                /*
                 * end sql query
                 */

                $loop = new WP_Query($sql_array);

                /*
                 * products loop
                 */
                while($loop->have_posts()) {
                        $loop->the_post();            
   
                        $post_url = get_permalink($post->ID);
                        $title = '';
                        $featured_image = '';
                        $description = '';
                        $created_date = '';
                        $category = '';
                        $buttons = '';

                        $image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID),$params['image_source']);

                        /*
                         * if no featured image for the product
                         */
                        if ($image[0] == '' || $image[0] == '/') {
                                $image[0] = plugin_dir_url( __FILE__ ).'images/placeholder.png';   
                        }

                        /*
                         * show title
                         */
                        if ($params['show_title'] === 'true') {
                                $title = '<h3 class="wp-posts-carousel-title">';
                                        $title .= '<a href="'. $post_url .'" title="'.$post->post_title.'">'.$post->post_title.'</a>';
                                $title .= '</h3>';                 
                        }
                        
                        /*
                         * show title
                         */
                        if ($params['show_category'] === 'true') {
                                $categories = get_the_terms($post->ID, $post_type_category);
                                
                                if ($categories) {
                                        $category = '<p class="wp-posts-carousel-categories">';
                                                foreach ($categories as $cat) {
                                                        $category .= '<a href="'.  get_category_link( $cat->term_id ).'" title="' . esc_attr( sprintf( __( "View all items in %s" ), $cat->name ) ) . '">'.$cat->name.'</a> ';
                                                }
                                        $category .= '</p>';   
                                }
                        }                        
                        
                        /*
                         * show tags
                         */                        
                        if ($params['show_tags'] == 'true' && $post_tag) {
                                $categories = get_the_terms($post->ID, $post_type_category);                                
                                
                                $tags = '<p class="wp-posts-carousel-tags">';
                                        $tags .= get_the_term_list(get_the_ID(), $post_tag, '', ' ', '' );
                                $tags .= '</p>';                                   
                        }                          
                        
                        /*
                         * show created date
                         */ 
                        if ($params['show_created_date'] === 'true') {
                                $created_date = '<p class="wp-posts-carousel-created-date">';
                                        $created_date .= get_the_date();
                                $created_date .= '</p>';                 
                        }    
                        
                        if ($params['show_featured_image'] === 'true') {
                                $featured_image = '<div class="wp-posts-carousel-image">';
                                        $featured_image .= '<a href="'. $post_url .'" title="'. __('Read more', 'wp-posts-carousel') .' '. $post->post_title .'">';
                                                $featured_image .= '<img src="'. $image[0] .'" alt="'. $post->post_title .'" style="max-width:'. $params['image_width'] .'%;max-height:'. $params['image_height'] .'%">';
                                        $featured_image .= '</a>';
                                $featured_image .= '</div>';
                        }
                        
                        /*
                         * show description
                         */
                        if ($params['show_description'] === 'true') {
                                $description = '<div class="wp-posts-carousel-desc">'.$post->post_excerpt.'</div>';
                        }      

                        /*
                         * show button
                         */
                        if ($params['show_more_button'] === 'true') {     
                                $buttons = '<p class="wp-posts-carousel-buttons">';
                                        $buttons .= '<a href="'. $post_url .'" class="wp-posts-carousel-more-button button" title="'.__('Read more', 'wp-posts-carousel').' '.$post->post_title.'">'.__('read more', 'wp-posts-carousel').'</a>';
                                $buttons .= '<p>';
                        }
            

                        /*
                         * list products
                         */
                        $out .= '<div class="wp-posts-carousel-slide slides-'.$params['items_to_show'].'">';
                                $out .= '<div class="wp-posts-carousel-container">';
                                        
                                        $out .= $featured_image;

                                        $out .= '<div class="wp-posts-carousel-details">';
                                                $out .= $title;                                                
                                                $out .= $created_date;
                                                $out .= $category;
                                                $out .= $description;  
                                                $out .= $tags;                                                                                              
                                                $out .= $buttons;              
                                        $out .= '</div>';
                                $out .= '</div>';
                        $out .= '</div>';
                }
                $out .= '</div>';        
        
                /*
                 * generate jQuery script for FlexCarousel         
                 */
                $out .= self::carousel($params);  
                return $out;
        }
    
        static function carousel($params = array()) { 
                if (empty($params)) {
                        return false;
                }
                $mouse_wheel = null;

                if ($params['mouse_wheel'] == 'true') {
                        $mouse_wheel = 'wpPostsCarousel'. $params['id'] .'.on("mousewheel", ".owl-stage", function(e) {
                                        if (e.deltaY > 0) {
                                            wpPostsCarousel'. $params['id'] .'.trigger("next.owl");
                                        } else {
                                            wpPostsCarousel'. $params['id'] .'.trigger("prev.owl");
                                        }
                                        e.preventDefault();
                                        });';  
                }

                $out = '<script type="text/javascript">        
                    jQuery(document).ready(function(e) {            
                        var wpPostsCarousel'. $params['id'] .' = jQuery("#wp-posts-carousel-'.$params['id'].'");
                        wpPostsCarousel'. $params['id'] .'.owlCarousel({
                            loop: '. $params['loop'] .',
                            nav: '. $params['nav'] .',
                            navSpeed: '. $params['nav_speed'] .', 
                            dots: '. $params['dots'] .',
                            dotsSpeed: '. $params['dots_speed'] .',
                            lazyLoad: '. $params['lazy_load'] .',
                            autoplay: '. $params['auto_play'] .',
                            autoplayHoverPause: '. $params['stop_on_hover'] .',
                            autoplayTimeout: '. $params['auto_play_timeout'] .',
                            autoplaySpeed:  '. $params['auto_play_timeout'] .',
                            margin: 5,
                            stagePadding: 0,
                            freeDrag: false,      
                            mouseDrag: '. $params['mouse_drag'] .',
                            touchDrag: '. $params['touch_drag'] .',
                            slideBy: '. $params['slide_by'] .',
                            fallbackEasing: "'. $params['easing'] .'",
                            responsiveClass: true,                    
                            navText: [ "'. __('previous', 'wp-posts-carousel') .'", "'. __('next', 'wp-posts-carousel') .'" ],
                            responsive:{
                                0:{
                                    items: 1
                                },
                                600:{
                                    items: '. ceil($params['items_to_show']/2) .',

                                },
                                1000:{
                                    items: '. $params['items_to_show'] .'
                                }
                            },
                            autoHeight: true
                        });
                        '. $mouse_wheel .'
                    });  
                </script>';

                return $out;
        }    
    
    
        public static function prepareSettings($settings) {                
                $checkboxes = array(
                                    'show_title'              => 'true',
                                    'show_created_date'       => 'true',
                                    'show_description'        => 'true',
                                    'show_category'           => 'true',
                                    'show_tags'               => 'false',
                                    'show_more_button'        => 'true',
                                    'show_featured_image'     => 'true',         

                                    'loop'                    => 'true',
                                    'auto_play'               => 'true',
                                    'stop_on_hover'           => 'true',
                                    'nav'                     => 'true',
                                    'dots'                    => 'true',
                                    'lazy_load'               => 'false',
                                    'mouse_drag'              => 'true',
                                    'mouse_wheel'             => 'true',
                                    'touch_drag'              => 'true'
                                    );

                foreach($checkboxes as $k => $v) {
                        if (!array_key_exists($k, $settings)) {
                                $settings[$k] = 'false';
                        } else { 
                                $settings[$k] = ($settings[$k] == 1 || $settings[$k] == 'true') ? 'true' : 'false';
                        }
                }

                $settings['id'] = self::generateId(); 
                
                /*
                 * if there are no all settings
                 */
                $defaults = self::getDefaults();
                foreach($defaults as $k => $v) {
                    if (!array_key_exists($k, $settings)) {
                        $settings[$k] = $defaults[$k];
                    }
                }
                return $settings;
        }
}
