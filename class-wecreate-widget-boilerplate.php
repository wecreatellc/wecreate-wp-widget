<?php
/**
 * The file that defines the widget
 *
 * @link       http://www.the_url_to_the_widget_repo_if_any.com
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
 * @author     Aurthor Name <authoremail@something.com>
 */

// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
  exit;
}

class WeCreate_Widget_Boilerplate extends WeCreate_Widget implements WeCreate_Widget_Interface {

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
  protected $widget_slug = 'wecreate-widget-boilerplate';

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
  protected $force_cache = true;


  /*--------------------------------------------------*/
  /* Constructor
  /*--------------------------------------------------*/

  /**
   * Specifies the classname and description, instantiates the widget,
   * loads localization files, and includes necessary stylesheets and JavaScript.
   */
  public function __construct() {

    parent::__construct(
        $this->get_widget_slug(),
        __( 'weCreate Widget Boilerplate', $this->get_widget_slug() ),
        array(
            'classname'  => $this->get_widget_slug().'-widget',
            'description' => __( 'Boilerplate class for widgets', $this->get_widget_slug() )
        )
    );

    // Register admin styles and scripts
    add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

    // Register front end styles and scripts
    add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) );

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
   * Outputs the content of the widget.
   *
   * @param array $args The array of form elements
   * @param array $instance The current instance of the widget
   *
   * @return null
   */
  public function widget( $args, $instance ) {

    // Determine a cache key
    // WARNING: If you use a cache key other than this, you will have to override
    //          _flush_widget_cache() & possibly _callback_updated_option() to accommodate
    //          your custom caching structure
    $cache_key = $this->cache_group .'_'. $this->get_widget_slug();

    if ( WP_CACHE || $this->force_cache ) {
      // Check if there is a cached output
      $cache = get_transient( $cache_key );

      if ( !is_array( $cache ) )
        $cache = array();

      if ( ! isset ( $args['widget_id'] ) )
        $args['widget_id'] = $this->id;

      if ( isset ( $cache[ $args['widget_id'] ] ) ) {
        return print "<!-- Served from Cache -->\n". $cache[ $args['widget_id'] ];
      }
    }

    extract( $args, EXTR_SKIP );

    $widget_string = $before_widget;

    ob_start();
    include( dirname( __FILE__ ) . '/views/widget.php' );
    $widget_string .= ob_get_clean();
    $widget_string .= $after_widget;


    $cache[ $args['widget_id'] ] = $widget_string;

    if ( WP_CACHE || $this->force_cache ) {
      set_transient( $cache_key, $cache, $this->cache_expire );
    }

    print $widget_string;

  } // end widget

  /**
   * Processes the widget's options to be saved.
   *
   * @param array $new_instance The new instance of values to be generated via the update.
   * @param array $old_instance The previous instance of values before the update.
   *
   * @return array new_instance The new instance of values to be generated via the update.
   */
  public function update( $new_instance, $old_instance ) {

    $instance = $new_instance;

    return $instance;

  } // end widget

  /**
   * Generates the administration form for the widget.
   *
   * @param array $instance The array of keys and values for the widget.
   *
   * @return null
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


  /*--------------------------------------------------*/
  /* Public Functions
  /*--------------------------------------------------*/

  /**
   * Registers and enqueues admin-specific styles.
   */
  public function register_admin_styles() {

    wp_enqueue_style( $this->get_widget_slug().'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ) );

  } // end register_admin_styles

  /**
   * Registers and enqueues admin-specific JavaScript.
   */
  public function register_admin_scripts() {

    wp_enqueue_script( $this->get_widget_slug().'-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array('jquery') );

  } // end register_admin_scripts

  /**
   * Registers and enqueues widget-specific styles.
   */
  public function register_widget_styles() {

    wp_enqueue_style( $this->get_widget_slug().'-widget-styles', plugins_url( 'css/widget.css', __FILE__ ) );

  } // end register_widget_styles

  /**
   * Registers and enqueues widget-specific scripts.
   */
  public function register_widget_scripts() {

    wp_enqueue_script( $this->get_widget_slug().'-script', plugins_url( 'js/widget.js', __FILE__ ), array('jquery') );

  } // end register_widget_scripts

} // end class

// You can choose to register the widget as soon as the class is loaded or elsewhere
// add_action( 'widgets_init', create_function( '', 'register_widget("LTBP_Top_Grossing_Posts_Widget");' ) );
