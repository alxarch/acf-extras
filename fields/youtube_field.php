<?php

class YouTube_Field extends acf_Field
{
    static protected $template = '<iframe class="youtube-player" frameborder="0" type="text/html" width="%width%" height="%height%" src="http://www.youtube.com/embed/%video_id%"></iframe>';

    function __construct($parent){
        $this->name = 'youtube';
        $this->parent = $parent;
        $this->title = __("YouTube Video");
    }

    function create_options($key, $field)
    {   
         ?>
            <tr class="field_option field_option_<?php echo $this->name; ?>">
            <td class="label">
                <label for=""><?php echo __("Dimensions"); ?></label>
            </td>
            <td>
            	<input type="text" 
                       style="width: 4em"
            	       id="field_<?php echo $key;?>_width"
            	       name="field[<?php echo $key?>][width]"
            	       value="<?php echo isset($field['width']) ? $field['width'] : 640 ?>"/>
            	<span>&times;</span>
            	<input type="text" 
                       style="width: 4em"
                       id="field_<?php echo $key;?>_height"
            	       name="field[<?php echo $key?>][height]"
            	       value="<?php echo isset($field['height']) ? $field['height'] : 385 ?>"/>
            </td>
            </tr>
            <?php
    }

    function create_field($field)
    {       
        ?>
        <div class="acf-youtube-field" 
             data-msg-confirm="<?php echo __('Select YouTube Video') ?>"
             data-msg-dialog-title="<?php echo __('Invalid video url.') ?>">
        <input class="acf-youtube-value" 
        	   type="hidden" 
        	   name="<?php echo $field['name'] ?>" 
        	   value="<?php echo $field['value'] ?>"/>

    	<input type="text" 
               class="acf-youtube-title" 
               onclick="acf_youtube.select(this.parentNode)"
               onfocus="acf_youtube.select(this.parentNode)">
        <a class="acf-button acf-youtube-go" onclick="acf_youtube.select(this.parentNode)">change</a>
        <div style="display:none" class="acf-youtube-dialog">
            <label><?php echo __('Video'); ?></label>
            <div class="acf-youtube-url">
            <input type="text" 
                   placeholder="<?php echo __('Paste youtube video url here...') ?>"/>
            <a class="acf-button acf-youtube-go">go</a>
            </div>
            <br>
        </div>
        </div>

        <?php
    }

    function get_value_for_api($post_id, $field){
        return strtr(self::$template, array(
            '%width%' => $field['width'],
            '%height%' => $field['height'],
            '%video_id%' => $parent::get_value($post_id, $field),
        ));
    } 

    function admin_print_styles()
    {
        wp_enqueue_style('wp-jquery-ui-dialog');
        wp_enqueue_style('acf-youtube', plugins_url('css/acf-youtube.css', dirname(__FILE__)));
    }

    function admin_print_scripts()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_script('acf-youtube', plugins_url('js/acf-youtube.js', dirname(__FILE__)));
    }
}