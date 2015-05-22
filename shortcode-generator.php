<?php
/*
Author: Marcin Gierada
Author URI: http://www.teastudio.pl/
Author Email: m.gierada@teastudio.pl
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

?>
		
<style type="text/css">
table {font-size:12px;}
</style>
<script type="text/javascript">
function insert_shortcode() {
    var shortcode = '[wp_posts_carousel';
    
    jQuery('#wp-posts-carousel-form').find(':input').filter(function() {
        var val = null;
        if(this.type != "button") {
            if(this.type == "checkbox") {  
                val = this.checked ? "true" : "false";
            }else {
                val = this.value;
            }
            shortcode += ' '+jQuery.trim( this.name )+'="'+jQuery.trim( val )+'"';
        }
    });

    shortcode +=']';

    tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
    tb_remove();
}
</script>

<div class="widget" id="wp-posts-carousel-form">
    <table cellspacing="5" cellpadding="5">
        <tr>
            <td colspan="2" align="left"><strong>---<?php _e('Display options', 'wp-posts-carousel') ?>---</strong></td>
        </tr>
        <tr>
            <td align="left"><?php _e('Template', 'wp-posts-carousel'); ?>:</td>
            <td>
                <select name="template" id="template" class="select">
                    <?php
                        $files_list = scandir(plugin_dir_path(__FILE__).'templates');
                        unset($files_list[0]);
                        unset($files_list[1]);
                        foreach($files_list as $filename) {
                            echo "<option value=\"".$filename."\">".$filename."</option>";
                        }
                    ?>
                </select>	
            </td>
        </tr>
        <tr>
            <td align="left"><?php _e('Post type', 'wp-posts-carousel'); ?>:</td>
            <td>
                <select name="post_type" id="post_type" class="select">
                <?php          
                    $taxonomies = get_post_types(array('public' => 'true', 'show_in_nav_menus' => true), 'objects');
                    foreach($taxonomies as $key => $type) {
                        echo "<option value=\"" .$key ."\">". $type->label ."</option>";
                    }
                ?>          
                </select>	
            </td>
        </tr>         
        <tr>
            <td align="left"><?php _e('Posts limit', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="text" name="all_items" id="all_items" value="10" size="5">
            </td>
        </tr>
        <tr>
            <td align="left"><?php _e('Ordering', 'wp-posts-carousel'); ?>:</td>
            <td>
                <select name="ordering" id="ordering" class="select">
                    <option value="asc"><?php _e("Ascending", 'wp-posts-carousel') ?></option>
                    <option value="desc"><?php _e("Descending", 'wp-posts-carousel') ?></option>              
                </select>	
            </td>
        </tr>   
        <tr>
            <td align="left"><?php _e('Category IDs', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="text" name="categories" id="categories" value="" size="30">
                <br />
                <small><?php _e('Please enter Category IDs with comma seperated.', 'wp-posts-carousel') ?></small>
            </td>
        </tr> 
        <tr>
            <td align="left"><?php _e('Tag IDs', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="text" name="tags" id="tags" value="" size="30">
                <br />
                <small><?php _e('Please enter Tag IDs with comma seperated.', 'wp-posts-carousel') ?></small>
            </td>
        </tr> 

        <tr>
            <td colspan="2" align="left">
                <br />
                <strong>---<?php _e('Post options', 'wp-posts-carousel') ?>---</strong>
            </td>
        </tr>  
        <tr>
            <td align="left"><?php _e('Show title', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="show_title" id="show_title" checked="checked">
            </td>
        </tr>	
       <tr>
            <td align="left"><?php _e('Show created date', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="show_created_date" id="show_created_date" checked="checked">
            </td>
        </tr>
        <tr>
            <td align="left"><?php _e('Show description', 'wp-posts-carousel'); ?>:</td>
            <td>
                <select name="show_description" id="show_description" class="select">
                    <option value="false"><?php _e("No", 'wp-posts-carousel') ?></option>
                    <option value="excerpt"><?php _e("Excerpt", 'wp-posts-carousel') ?></option>
                    <option value="content"><?php _e("Full content", 'wp-posts-carousel') ?></option>              
                </select>	
            </td>
        </tr>   
        <tr>
            <td><?php _e('Show category', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="show_category" id="show_category" checked="checked">
            </td>
        </tr>   
        <tr>
            <td><?php _e('Show tags', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="show_tags" id="show_tags">
            </td>
        </tr>          
        <tr>
            <td><?php _e('Show more button', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="show_more_button" id="show_more_button" checked="checked">
            </td>
        </tr> 
        <tr>
            <td><?php _e('Show featured image', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="show_featured_image" id="show_featured_image" checked="checked">
            </td>
        </tr>          
        <tr>
            <td align="left"><?php echo _e('Image source', 'wp-posts-carousel'); ?>:</td>
            <td>
                <select name="image_source" id="image_source" class="select">
                    <option value="thumbnail"><?php _e("Thumbnail") ?></option>
                    <option value="medium"><?php _e("Medium") ?></option>
                    <option value="large"><?php _e("Large") ?></option>
                    <option value="full"><?php _e("Full") ?></option>
                </select>
            </td>
        </tr>	
        <tr>
            <td align="left"><?php _e('Image height', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="text" name="image_height" id="image_height" value="100" size="5">%
            </td>
        </tr>
        <tr>
            <td align="left"><?php _e('Image width', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="text" name="image_width" id="image_width" value="100" size="5">%
            </td>
        </tr>	
        

        <tr>
            <td colspan="2" align="left">
                <br />
                <strong>---<?php _e('Carousel options', 'wp-posts-carousel') ?>---</strong>
            </td>
        </tr>  
        <tr>
            <td align="left"><?php _e('Items to show', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="text" name="items_to_show" id="items_to_show" value="4" size="5">
            </td>
        </tr>  
        <tr>
            <td align="left"><?php _e('Slide by', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="text" name="slide_by" id="slide_by" value="1" size="5">
                <br />
                <small><?php echo _e("Number of elements to slide.", "wp-posts-carousel") ?></small>                
            </td>
        </tr> 
        <tr>
            <td align="left"><?php _e('Margin', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="text" name="margin" id="margin" value="5" size="5">[px]
                <br />
                <small><?php echo _e("Margin between items.", "wp-posts-carousel") ?></small>                  
            </td>
        </tr>         
        <tr>
            <td align="left"><?php _e('Inifnity loop', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="loop" id="loop" checked="checked">
                <br />
                <small><?php echo _e("Duplicate last and first items to get loop illusion.", "wp-posts-carousel") ?></small>                
            </td>
        </tr>	   
        <tr>
            <td align="left"><?php _e('Auto play', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="auto_play" id="auto_play" checked="checked">
            </td>
        </tr>	 
        <tr>
            <td align="left"><?php _e('Pause on mouse hover', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="stop_on_hover" id="stop_on_hover" checked="checked">
            </td>
        </tr>     
        <tr>
            <td align="left"><?php _e('Autoplay interval timeout', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="text" name="auto_play_timeout" id="auto_play_timeout" value="1200" size="5">[ms]
            </td>
        </tr>
        <tr>
            <td align="left"><?php _e('Show "next" and "prev" buttons', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="nav" id="nav" checked="checked">
            </td>
        </tr>    
        <tr>
            <td align="left"><?php _e('Navigation speed', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="text" name="nav_speed" id="nav_speed" value="800" size="5">[ms]
            </td>
        </tr>   
        <tr>
            <td align="left"><?php _e('Show dots navigation', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="dots" id="dots" checked="checked">
            </td>
        </tr>  
        <tr>
            <td align="left"><?php _e('Dots speed', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="text" name="dots_speed" id="dots_speed" value="800" size="5">[ms]
            </td>
        </tr>         
        <tr>
            <td align="left"><?php _e('Delays loading of images', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="lazy_load" id="lazy_load">
                <br />
                <small><?php echo _e("Images outside of viewport won't be loaded before user scrolls to them. Great for mobile devices to speed up page loadings.","wp-posts-carousel"); ?></small>                              
            </td>
        </tr>  	
        <tr>
            <td align="left"><?php _e('Mouse events', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="mouse_drag" id="mouse_drag" checked="checked">
            </td>
        </tr> 
        <tr>
            <td align="left"><?php _e('Mousewheel scrolling', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="mouse_wheel" id="mouse_wheel" checked="checked">
            </td>
        </tr>  
        <tr>
            <td align="left"><?php _e('Touch events', 'wp-posts-carousel'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="touch_drag" id="touch_drag" checked="checked">
            </td>
        </tr> 
        <tr>
            <td align="left"><?php echo _e('Animation', 'wp-posts-carousel'); ?>:</td>
            <td>
                <select name="easing" id="easing" class="select">
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
                        echo "<option value=\"".$key."\">".$list."</option>";
                  }
                ?>    
                </select>
            </td>
        </tr>	        
        <tr>
            <td colspan="2">
                <input type="button" class="button button-primary button-large" value="<?php _e('Insert Shortcode', 'wp-posts-carousel') ?>" onClick="insert_shortcode();">
            </td>
        </tr>
    </table>
</div>
