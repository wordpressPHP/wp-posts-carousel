<?php
/*
Author: Marcin Gierada
Author URI: http://www.teastudio.pl/
Author Email: m.gierada@teastudio.pl
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


/*
 * widget
 */
class WpPostsCarouselWidget extends WP_Widget {

        function WpPostsCarouselWidget() {
                $widget_ops = array("classname" => "widget_wp_posts_carousel","description" => __("Show posts in Owl Carousel", "wp-posts-carousel"));
                $this->WP_Widget("wp_posts_carousel", __("WP Posts Carousel", "wp-posts-carousel"), $widget_ops);
        }
        
        function widget( $args, $instance ) {
                extract( $args );

                $title = apply_filters("widget_title", $instance["title"]);

                echo $before_widget;

                if ($title) {
                        echo $before_title . $title . $after_title;
                }

                echo WpPostsCarouselGenerator::generate($instance);
                echo $after_widget;
        }
        
        function update ($new_instance, $old_instance) {
                return $new_instance;
        }
          
/**
 * the configuration form.
 */
function form($instance) {
        /*
         * load defaults if new
         */
        if(empty($instance)) {
                $instance = WpPostsCarouselGenerator::getDefaults();
        }      

?>
    <p>
        <label for="<?php echo $this->get_field_id("title"); ?>"><?php _e("Title"); ?>:</label>        
        <input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo esc_attr(array_key_exists('title', $instance) ? $instance["title"] : ''); ?>" />
    </p>

    <p>
        <strong>--- <?php _e("Display options", "wp-posts-carousel") ?> ---</strong>
    </p> 
    <p>
        <label for="<?php echo $this->get_field_id("template"); ?>"><?php _e("Template", "wp-posts-carousel"); ?>:</label>
        <br />
        <select name="<?php echo $this->get_field_name("template"); ?>" id="<?php echo $this->get_field_id("template"); ?>" class="select">
            <?php            
              $files_list = scandir(plugin_dir_path(__FILE__)."templates/");
              unset($files_list[0]);
              unset($files_list[1]);
              foreach($files_list as $list) {
                echo "<option value=\"".$list."\" ". (esc_attr($instance["template"]) == $list ? "selected=\"selected\"" : null) .">". $list ."</option>";
              }
            ?>
        </select>
    </p> 
    <p>
        <label for="<?php echo $this->get_field_id("post_type"); ?>"><?php _e("Post type", "wp-posts-carousel"); ?>:</label>
        <br />
        <select name="<?php echo $this->get_field_name("post_type"); ?>" id="<?php echo $this->get_field_id("post_type"); ?>" class="select">
          <?php          
            $taxonomies = get_post_types(array('public' => 'true', 'show_in_nav_menus' => true), 'objects');
            foreach($taxonomies as $key => $type) {
                echo "<option value=\"" .$key ."\" ". (esc_attr($instance["post_type"]) == $key ? 'selected="selected"' : null) .">". $type->label ."</option>";
            }
          ?>
        </select>
    </p>       
    <p>
        <label for="<?php echo $this->get_field_id("all_items"); ?>"><?php _e("Posts limit", "wp-posts-carousel"); ?>:</label>
        <br />
        <input size="5" id="<?php echo $this->get_field_id("all_items"); ?>" name="<?php echo $this->get_field_name("all_items"); ?>" type="text" value="<?php echo esc_attr($instance["all_items"]); ?>" />
    </p>     
    <p>
        <label for="<?php echo $this->get_field_id("ordering"); ?>"><?php _e("Ordering", "wp-posts-carousel"); ?>:</label>
        <br />
        <select name="<?php echo $this->get_field_name("ordering"); ?>" id="<?php echo $this->get_field_id("ordering"); ?>" class="select">
          <?php            
            $ordering_list = array("asc" => __("Ascending", "wp-posts-carousel"),
                                   "desc" => __("Descending", "wp-posts-carousel")
                                  );
            foreach($ordering_list as $key => $list) {
                echo "<option value=\"" .$key ."\" ". (esc_attr($instance["ordering"]) == $key ? 'selected="selected"' : null) .">". $list ."</option>";
            }
          ?>
        </select>
    </p>    
    <p>
        <label for="<?php echo $this->get_field_id("categories"); ?>"><?php _e("Category IDs", "wp-posts-carousel"); ?>:</label>
        <input class="widefat" id="<?php echo $this->get_field_id("categories"); ?>" name="<?php echo $this->get_field_name("categories"); ?>" type="text" value="<?php echo esc_attr($instance["categories"]); ?>" />
        <br />
        <small><?php _e("Please enter Category IDs with comma seperated.", "wp-posts-carousel") ?></small>
    </p> 
    
    
    <p>
        <strong>--- <?php _e("Post options", "wp-posts-carousel") ?> ---</strong>
    </p>  
    <p>  
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("show_title"); ?>" name="<?php echo $this->get_field_name("show_title"); ?>" <?php array_key_exists('show_title', $instance) ? checked( (bool) $instance["show_title"], true ): null; ?> value="1" />
        <label for="<?php echo $this->get_field_id("show_title"); ?>"><?php _e("Show title", "wp-posts-carousel"); ?></label>
    </p>
    <p>
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("show_created_date"); ?>" name="<?php echo $this->get_field_name("show_created_date"); ?>" <?php array_key_exists('show_created_date', $instance) ? checked( (bool) $instance["show_created_date"], true ): null; ?> value="1" />
        <label for="<?php echo $this->get_field_id("show_created_date"); ?>"><?php _e("Show created date", "wp-posts-carousel"); ?></label>
    </p>    
    <p>
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("show_description"); ?>" name="<?php echo $this->get_field_name("show_description"); ?>" <?php array_key_exists('show_description', $instance) ? checked( (bool) $instance["show_description"], true ): null; ?> value="1" />
        <label for="<?php echo $this->get_field_id("show_description"); ?>"><?php _e("Show description", "wp-posts-carousel"); ?></label>
    </p> 
    <p>
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("show_category"); ?>" name="<?php echo $this->get_field_name("show_category"); ?>" <?php array_key_exists('show_category', $instance) ? checked( (bool) $instance["show_category"], true ): null; ?> value="1" />
        <label for="<?php echo $this->get_field_id("show_category"); ?>"><?php _e("Show category", "wp-posts-carousel"); ?></label>
    </p>     
    <p>
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("show_tags"); ?>" name="<?php echo $this->get_field_name("show_tags"); ?>" <?php array_key_exists('show_tags', $instance) ? checked( (bool) $instance["show_tags"], true ): null; ?> value="1" />
        <label for="<?php echo $this->get_field_id("show_tags"); ?>"><?php _e("Show tags", "wp-posts-carousel"); ?></label>
    </p>      
    <p>
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("show_more_button"); ?>" name="<?php echo $this->get_field_name("show_more_button"); ?>" <?php array_key_exists('show_more_button', $instance) ? checked( (bool) $instance["show_more_button"], true ): null; ?> value="1" />
        <label for="<?php echo $this->get_field_id("show_more_button"); ?>"><?php _e("Show more button", "wp-posts-carousel"); ?></label>
    </p>  
    <p>
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("show_featured_image"); ?>" name="<?php echo $this->get_field_name("show_featured_image"); ?>" <?php array_key_exists('show_featured_image', $instance) ? checked( (bool) $instance["show_featured_image"], true ): null; ?> value="1" />
        <label for="<?php echo $this->get_field_id("show_featured_image"); ?>"><?php _e("Show featured image", "wp-posts-carousel"); ?></label>
    </p>     
    <p>
        <label for="<?php echo $this->get_field_id("image_source"); ?>"><?php echo _e("Image source", "wp-posts-carousel"); ?>:</label>
        <br />
        <select name="<?php echo $this->get_field_name("image_source"); ?>" id="<?php echo $this->get_field_id("image_source"); ?>" class="select">
            <?php            
              $source_list = array("thumbnail"  => __("Thumbnail"),
                                   "medium"     => __("Medium"),
                                   "large"      => __("Large"),
                                   "full"       => __("Full"),                  
                                  );
              foreach($source_list as $key => $list) {
                    echo "<option value=\"". $key ."\" ". (esc_attr($instance["image_source"]) == $key ? 'selected="selected"' : null) .">". $list ."</option>";
              }
            ?>
        </select>
    </p>      
    <p>
        <label for="<?php echo $this->get_field_id("image_height"); ?>"><?php _e("Image height", "wp-posts-carousel"); ?>:</label>
        <br />
        <input size="10" id="<?php echo $this->get_field_id("image_height"); ?>" name="<?php echo $this->get_field_name("image_height"); ?>" type="text" value="<?php echo esc_attr($instance["image_height"]); ?>" />%
    </p>
    <p>
        <label for="<?php echo $this->get_field_id("image_width"); ?>"><?php _e("Image width", "wp-posts-carousel"); ?>:</label>
        <br />
        <input size="10" id="<?php echo $this->get_field_id("image_width"); ?>" name="<?php echo $this->get_field_name("image_width"); ?>" type="text" value="<?php echo esc_attr($instance["image_width"]); ?>" />%
    </p>    
    
    
    <p>
        <strong>--- <?php _e("Carousel options", "wp-posts-carousel") ?> ---</strong>
    </p>   
    <p>
        <label for="<?php echo $this->get_field_id("items_to_show"); ?>"><?php _e("Items to show", "wp-posts-carousel"); ?>:</label>
        <br />
        <input size="5" id="<?php echo $this->get_field_id("items_to_show"); ?>" name="<?php echo $this->get_field_name("items_to_show"); ?>" type="text" value="<?php echo esc_attr($instance["items_to_show"]); ?>" />
    </p>  
    <p>
        <label for="<?php echo $this->get_field_id("slide_by"); ?>"><?php _e("Slide by", "wp-posts-carousel"); ?>:</label>
        <br />
        <input size="5" id="<?php echo $this->get_field_id("slide_by"); ?>" name="<?php echo $this->get_field_name("slide_by"); ?>" type="text" value="<?php echo esc_attr($instance["slide_by"]); ?>" />
        <br />
        <small><?php echo _e("Number of elements to slide.", "wp-posts-carousel") ?></small>
    </p>     
    <p>
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("loop"); ?>" name="<?php echo $this->get_field_name("loop"); ?>" <?php array_key_exists('loop', $instance) ? checked( (bool) $instance["loop"], true ): null; ?> value="1" />
        <label for="<?php echo $this->get_field_id("loop"); ?>"><?php _e("Inifnity loop", "wp-posts-carousel"); ?></label>
        <br />
        <small><?php echo _e("Duplicate last and first items to get loop illusion.", "wp-posts-carousel") ?></small>
    </p>    
    <p>
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("auto_play"); ?>" name="<?php echo $this->get_field_name("auto_play"); ?>" <?php array_key_exists('auto_play', $instance) ? checked( (bool) $instance["auto_play"], true ): null; ?> value="1" />
        <label for="<?php echo $this->get_field_id("auto_play"); ?>"><?php _e("Auto play", "wp-posts-carousel"); ?></label>
    </p>   
    <p>
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("stop_on_hover"); ?>" name="<?php echo $this->get_field_name("stop_on_hover"); ?>" <?php array_key_exists('stop_on_hover', $instance) ? checked( (bool) $instance["stop_on_hover"], true ): null; ?> value="1"/>
        <label for="<?php echo $this->get_field_id("stop_on_hover"); ?>"><?php _e("Pause on mouse hover", "wp-posts-carousel"); ?></label>
    </p>  
    <p>
        <label for="<?php echo $this->get_field_id("auto_play_timeout"); ?>"><?php _e("Autoplay interval timeout", "wp-posts-carousel"); ?>:</label>
        <br />
        <input size="5" id="<?php echo $this->get_field_id("auto_play_timeout"); ?>" name="<?php echo $this->get_field_name("auto_play_timeout"); ?>" type="text" value="<?php echo esc_attr($instance["auto_play_timeout"]); ?>" />[ms]
    </p>  
    <p>
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("nav"); ?>" name="<?php echo $this->get_field_name("nav"); ?>" <?php array_key_exists('nav', $instance) ? checked( (bool) $instance["nav"], true ): null; ?> value="1"/>
        <label for="<?php echo $this->get_field_id("nav"); ?>"><?php _e("show \"next\" and \"prev\" buttons", "wp-posts-carousel"); ?></label>
    </p>  
    <p>
        <label for="<?php echo $this->get_field_id("nav_speed"); ?>"><?php _e("Navigation speed", "wp-posts-carousel"); ?>:</label>
        <br />
        <input size="5" id="<?php echo $this->get_field_id("nav_speed"); ?>" name="<?php echo $this->get_field_name("nav_speed"); ?>" type="text" value="<?php echo esc_attr($instance["nav_speed"]); ?>" />[ms]
    </p>     
    <p>
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("dots"); ?>" name="<?php echo $this->get_field_name("dots"); ?>" <?php array_key_exists('dots', $instance) ? checked( (bool) $instance["dots"], true ): null; ?> value="1"/>
        <label for="<?php echo $this->get_field_id("dots"); ?>"><?php _e("Show dots navigation", "wp-posts-carousel"); ?></label>
    </p> 
    <p>
        <label for="<?php echo $this->get_field_id("dots_speed"); ?>"><?php _e("Dots speed", "wp-posts-carousel"); ?>:</label>
        <br />
        <input size="5" id="<?php echo $this->get_field_id("dots_speed"); ?>" name="<?php echo $this->get_field_name("dots_speed"); ?>" type="text" value="<?php echo esc_attr($instance["dots_speed"]); ?>" />[ms]
    </p>     
    <p>
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("lazy_load"); ?>" name="<?php echo $this->get_field_name("lazy_load"); ?>" <?php array_key_exists('lazy_load', $instance) ? checked( (bool) $instance["lazy_load"], true ): null; ?> value="1"/>
        <label for="<?php echo $this->get_field_id("lazy_load"); ?>"><?php _e("Delays loading of images", "wp-posts-carousel"); ?></label>
        <br />
        <small><?php echo _e("Images outside of viewport won't be loaded before user scrolls to them. Great for mobile devices to speed up page loadings.","wp-posts-carousel"); ?></small>
    </p>   
    <p>
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("mouse_drag"); ?>" name="<?php echo $this->get_field_name("mouse_drag"); ?>" <?php array_key_exists('mouse_drag', $instance) ? checked( (bool) $instance["mouse_drag"], true ): null; ?> value="1"/>
        <label for="<?php echo $this->get_field_id("mouse_drag"); ?>"><?php _e("Mouse events", "wp-posts-carousel"); ?></label>
    </p> 
    <p>
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("mouse_wheel"); ?>" name="<?php echo $this->get_field_name("mouse_wheel"); ?>" <?php array_key_exists('mouse_wheel', $instance) ? checked( (bool) $instance["mouse_wheel"], true ): null; ?> value="1"/>
        <label for="<?php echo $this->get_field_id("mouse_wheel"); ?>"><?php _e("Mousewheel scrolling", "wp-posts-carousel"); ?></label>
    </p>      
    <p>
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("touch_drag"); ?>" name="<?php echo $this->get_field_name("touch_drag"); ?>" <?php array_key_exists('touch_drag', $instance) ? checked( (bool) $instance["touch_drag"], true ): null; ?> value="1"/>
        <label for="<?php echo $this->get_field_id("touch_drag"); ?>"><?php _e("Touch events", "wp-posts-carousel"); ?></label>
    </p> 
    <p>
        <label for="<?php echo $this->get_field_id("easing"); ?>"><?php echo _e("Animation", "wp-posts-carousel"); ?>:</label>
        <br />
        <select name="<?php echo $this->get_field_name("easing"); ?>" id="<?php echo $this->get_field_id("easing"); ?>" class="select">
            <?php            
              $source_list = array("linear"             => "linear",
                                   "swing"              => "swing",
                                   "easeInQuad"         => "easeInQuad",
                                   "easeOutQuad"        => "easeOutQuad",   
                                   "easeInOutQuad"      => "easeInOutQuad",
                                   "easeInCubic"        => "easeInCubic",
                                   "easeOutCubic"       => "easeOutCubic",
                                   "easeInOutCubic"     => "easeInOutCubic",
                                   "easeInQuart"        => "easeInQuart",
                                   "easeOutQuart"       => "easeOutQuart",
                                   "easeInOutQuart"     => "easeInOutQuart",
                                   "easeInQuint"        => "easeInQuint",
                                   "easeOutQuint"       => "easeOutQuint",
                                   "easeInOutQuint"     => "easeInOutQuint",
                                   "easeInExpo"         => "easeInExpo",
                                   "easeOutExpo"        => "easeOutExpo",
                                   "easeInOutExpo"      => "easeInOutExpo",
                                   "easeInSine"         => "easeInSine",
                                   "easeOutSine"        => "easeOutSine",
                                   "easeInOutSine"      => "easeInOutSine",
                                   "easeInCirc"         => "easeInCirc",
                                   "easeOutCirc"        => "easeOutCirc",
                                   "easeInOutCirc"      => "easeInOutCirc",
                                   "easeInElastic"      => "easeInElastic",
                                   "easeOutElastic"     => "easeOutElastic",
                                   "easeInOutElastic"   => "easeInOutElastic",
                                   "easeInBack"         => "easeInBack",
                                   "easeOutBack"        => "easeOutBack",
                                   "easeInOutBack"      => "easeInOutBack",
                                   "easeInBounce"       => "easeInBounce",
                                   "easeOutBounce"      => "easeOutBounce",
                                   "easeInOutBounce"    => "easeInOutBounce"                   
                                  );

             
              foreach($source_list as $key => $list) {
                    echo "<option value=\"". $key ."\" ". (esc_attr($instance["easing"]) == $key ? 'selected="selected"' : null) .">". $list ."</option>";
              }
            ?>
        </select>
    </p>     
<?php
    }
}
add_action("widgets_init", create_function("", "return register_widget('WpPostsCarouselWidget');"));
?>