<?php
/**
 * ACF Field for names.
 *
 * @author Alexandros Sigalas
 *
 */
class Name_Field extends acf_Field
{
    static protected $default_format = '%F %L';
    static protected $parts = array(
        'prefix' => array('size' => 4, 'show' => false, 'label' => null, 'required' => false), 
        'first' => array('size' => 8, 'show' => true, 'label' => null, 'required' => false),
        'middle' => array('size' => 6, 'show' => false, 'label' => null, 'required' => false), 
        'last' => array('size' => 12, 'show' => true, 'label' => null, 'required' => true), 
        'suffix' => array('size' => 4, 'show' => false, 'label' => null, 'required' => false)
    );

    function __construct($parent){
        $this->name = 'name';
        $this->parent = $parent;
        $this->title = __("Name");
    }

    protected static function _part_option($part, $opt, $field){
      return isset($field[$part.'_'.$opt]) ? $field[$part.'_'.$opt] : self::$parts[$part][$opt];
    }
    
    protected function _part_options_row($key, $part, $field){
      
      $show = (boolean) self::_part_option($part, 'show', $field);
      
      $required = (boolean) self::_part_option($part, 'required', $field);
      
      $label = self::_part_option($part, 'label', $field);
      $label = null === $label ? ucfirst($part) : $label;
      $size = self::_part_option($part, 'size', $field);
      
      $result = <<<HTML
      <tr>
        <td align="right">%s</td>
        <td><input type="checkbox" name="fields[%s][%s_show]" value="1" %s/></td>
        <td><input type="checkbox" name="fields[%s][%s_required]" value="1" %s/></td>
        <td><input type="text" name="fields[%s][%s_label]" value="%s"/></td>
        <td><input type="text" name="fields[%s][%s_size]" value="%d"/></td>
      </tr>
HTML;
      return sprintf($result, 
        $part,
        $key, $part, $show ? 'checked="checked"' : '',
        $key, $part, $required ? 'checked="checked"' : '',
        $key, $part, $label,
        $key, $part, $size
      );
    }

    function create_options($key, $field)
    {   
         ?>
            <tr class="field_option field_option_<?php echo $this->name; ?>">
            <td class="label">
                <label for=""><?php echo __("Name parts"); ?></label>
            </td>
            <td>
              <table class="widefat" style="width: auto">
                <thead>
                  <tr>
                    <th style="width: 6em">Part</th>
                    <th style="width: 4em">Show</th>
                    <th style="width: 4em">Required</th>
                    <th style="width: 12em">Label</th>
                    <th style="width: 4em">Size</th>
                  </tr>
                </thead>
                <tbody>
                
                <?php 
            // defaults
            foreach (self::$parts as $part => $opts) {
                echo $this->_part_options_row($key, $part, $field);
            }
            ?>
                </tbody>
              </table>
                </td>
            </tr>
            <tr>
              <td class="label">
                <label for="field_<?php echo $key ?>_format">
                  <?php echo __('Default format') ?>
                </label>
                <p class="description">
                  <b>%F, %f</b>: First name (full, initial)<br>
                  <b>%L, %l</b>: Last name (full, initial)<br>
                  <b>%M, %m</b>: Middle name (full, initial)<br>
                  <b>%p, %s</b>: Prefix, Suffix
                </p>
              </td>
              <td>
                <input type="text" 
                       id="field_<?php echo $key ?>_format"
                       name="field[<?php echo $key ?>][name_format]"
                       value="<?php echo isset($field['name_format']) ? $field['name_format'] : self::$default_format ?>" />
              </td>
            </tr>
            <?php
        
    }

    function create_field($field)
    {       
        // defaults
        if($field['value'] == "")
            $field['value'] = str_repeat('|', count(self::$parts) - 1);

        $value = explode('|', $field['value']);
        ?>
        <div class="acf-name-field">
        <input class="acf-name-value" type="hidden" name="<?php echo $field['name'] ?>" value="<?php echo $field['value'] ?>"/>
        <?php
        foreach (array_keys(self::$parts) as $i => $part)
        {
            if(isset($field[$part.'_show']) && $field[$part.'_show']){
                $id = sprintf('%s_%s', $this->name, $part);
                $name = sprintf('%s[%s]', $this->name, $part);
                $size = self::_part_option($part, 'size', $field);
                $required = self::_part_option($part, 'required', $field);
                $label = self::_part_option($part, 'label', $field);
            ?>
            <label for="<?php echo $id ?>" class="acf-name-part">
                <?php echo $label ?>
                <?php if($required): ?>
                <span class="required">*</span>
                <?php endif ?>
                <br/>
                <input type="text"
                       value="<?php echo $value[$i] ?>" 
                       class="acf-name-part-<?php echo $part ?> <?php echo $required ? 'required' : '' ?>" 
                       name="<?php echo $name ?>" 
                       id="<?php echo $id ?>"
                       style="width: <?php echo $size ?>em"/>
            </label>

            <?php
            }
        }
        ?>
        </div>
        <?php
    }

  function admin_print_scripts()
  {
    wp_enqueue_script('jquery');
    wp_enqueue_script('acf-name', plugins_url('js/acf-name.js', dirname(__FILE__)));
   
  }

  function admin_print_styles()
  {
      wp_enqueue_style('acf-name', plugins_url('css/acf-name.css', dirname(__FILE__)));
  }

  function get_value_for_api($post_id, $field){
    $value = parent::get_value($post_id, $field);
    $format = isset($field['name_format']) ? $field['name_format'] : self::$default_format;
    
    $name = self::unserialize($value);
    return self::format_name($name, $format);
  }

  static public function format_name($name, $format){
    $F = isset($name['first']) ? $name['first'] : '';
    $f = mb_substr($F, 0, 1);
    $f = $f ? "$f." : '';
    
    $L = isset($name['last']) ? $name['last'] : '';
    $l = mb_substr($L, 0, 1);
    $l = $l ? "$l." : '';

    $M = isset($name['middle']) ? $name['middle'] : '';
    $m = mb_substr($M, 0, 1);
    $m = $m ? "$m." : '';

    return strtr($format, array(
      '%F' => $F,
      '%f' => $f,
      '%M' => $M,
      '%m' => $m,
      '%L' => $L,
      '%l' => $l,
      '%s' => isset($name['suffix']) ? $name['suffix'] : '',
      '%p' => isset($name['prefix']) ? $name['prefix'] : '',
    ));
  }

  static protected function unserialize($value){
    $parts = explode('|', $value);
    $result = array();
    foreach (array_keys(self::$parts) as $i => $part) {
      $result[$part] = $parts[$i];
    }

    return $result;
  }
}
