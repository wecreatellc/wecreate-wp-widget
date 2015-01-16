<?php
/**
 * The file that defines the widget
 *
 * @link       https://github.com/wecreatellc/wecreate-wp-widget
 * @since      1.0.0
 *
 * @package    wecreate
 * @subpackage wecreate/widgets
 */

/**
 * The  widget class
 *
 * @since      1.0.0
 * @package    wecreate
 * @subpackage wecreate/widgets
 * @author     Zach Lanich <zach@wecreatewebsites.net>
 */

// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
  exit;
}

abstract class WeCreate_Widget extends WP_Widget {

  /**
   * Unique identifier for your widget.
   *
   * The variable name is used as the text domain when internationalizing strings
   * of text. Its value should match the Text Domain file header in the main
   * widget file.
   *
   * @var string $widget_slug A unique slug name for your widget ie. my-widget
   * @since 1.0.0
   */
  protected $widget_slug = 'wecreate-widget';

  /**
   * @var string $cache_group Cache group name
   * @since 1.0.0
   */
  protected $cache_group = 'widget';

  /**
   * @var int $cache_expire Generic expiry time for widget cache
   * @since 1.0.0
   */
  protected $cache_expire = 86400; // 1 Day

  /**
   * @var bool $instance_update_purge Purge widget instance cache on instance update
   * @since 1.0.0
   */
  protected $instance_update_purge = true;

  /**
   * @var bool $force_cache Force caching even if WP_CACHE is false
   * @since 1.0.0
   */
  protected $force_cache = false;

  /*--------------------------------------------------*/
  /* Constructor
  /*--------------------------------------------------*/

  /**
   * Specifies the classname and description, instantiates the widget,
   * loads localization files, and includes necessary stylesheets and JavaScript.
   *
   * @param string $widget_slug Unique slug name for your widget ie. my-widget
   * @param string $widget_name Human readable name for your widget ie. My Widget
   * @param array $widget_options Should provide keys 'classname' (widget css class) & 'description'
   */
  public function __construct( $widget_slug, $widget_name, $widget_options = array() ) {

    parent::__construct(
        $widget_slug,
        $widget_name,
        $widget_options
    );

    // Refreshing the widget's cached output with widget update
    if ( $this->instance_update_purge ) {
      add_action( 'update_option_'. $this->option_name, array( $this, '_callback_updated_option' ) );
    }

  } // end constructor


  /**
   * Return the widget slug.
   *
   * @since    1.0.0
   *
   * @return    Widget slug variable.
   */
  public function get_widget_slug() {
    return $this->widget_slug;
  }

  /*--------------------------------------------------*/
  /* Widget API Functions
  /*--------------------------------------------------*/

  /**
   * Outputs the content of the widget - Must be overridden
   *
   * @param array $args The array of form elements
   * @param array $instance The current instance of the widget
   *
   * @return null
   *
   * @todo Create in Interface
   */
  public function widget( $args, $instance ) {}

  /**
   * Processes the widget's options to be saved - Can be overridden if need be
   *
   * @param array $new_instance The new instance of values to be generated via the update.
   * @param array $old_instance The previous instance of values before the update.
   *
   * @return array new_instance The new instance of values to be generated via the update.
   *
   * @todo Create in Interface
   */
  public function update( $new_instance, $old_instance ) {

    $instance = $new_instance;

    return $instance;

  } // end widget

  /**
   * Generates the administration form for the widget - Can be overridden if need be
   *
   * @param array $instance The array of keys and values for the widget.
   *
   * @return null
   *
   * @todo Create in Interface
   */
  public function form( $instance ) {

    // Define default values for your variables
    $defaults = array(
        'title' => 'weCreate Widget Boilerplate'
    );

    $instance = wp_parse_args( (array) $instance, $defaults );

    // Display the admin form
    include( dirname( __FILE__ ) . '/views/admin.php' );

  } // end form

  /**
   * Checks to see if one of the instances of this widget was updated & clears
   * the widget instance cache if so
   *
   * Callback for action 'updated_option'
   *
   * @since 1.0.0
   *
   * @param string $option WP option name
   */
  public function _callback_updated_option( $option ) {

    // One of the instances for this widget were updated

    $widget_id = $this->number;
    $this->_flush_widget_cache( $widget_id );

  }

  /**
   * Generic widget instance update cache-clearing mechanism
   *
   * Clears widget cache for all instances or specified instance
   *
   * @since 1.0.0
   *
   * @param int $widget_id Widget instance ID - optionally clears cache for only that instance
   * @param int $cache_key Widget Cache Key - allows passing in of custom cache key
   */
  protected function _flush_widget_cache( $widget_id = null, $cache_key = null ) {

    if ( is_null( $cache_key ) ) {
      $cache_key = $this->cache_group .'_'. $this->get_widget_slug();
    }

    if ( ! is_null( $widget_id ) ) {
      $full_widget_id = $this->id_base .'-'. $widget_id;
      $cache = get_transient( $cache_key );

      if ( is_array( $cache ) && array_key_exists( $full_widget_id, $cache ) ) {
        unset( $cache[ $full_widget_id ] );

        if ( WP_CACHE || $this->force_cache ) {
          set_transient( $cache_key, $cache, $this->cache_expire );
        }
      }
    }
    else {
      delete_transient( $cache_key );
    }

  }

} // end class