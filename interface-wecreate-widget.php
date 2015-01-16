<?php
/**
 * The file that defines the weCreate Widget Interface
 *
 * @link       https://github.com/wecreatellc/wecreate-wp-widget
 * @since      1.0.0
 *
 * @package    wecreate
 * @subpackage wecreate/widgets
 */

/**
 * The weCreate Widget Interface
 *
 * Widgets that implement this class should also inherit from abstract WeCreate_Widget
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

interface WeCreate_Widget_Interface {

  /*--------------------------------------------------*/
  /* Constructor
  /*--------------------------------------------------*/

  /**
   * Specifies the classname and description, instantiates the widget,
   * loads localization files, and includes necessary stylesheets and JavaScript.
   *
   * Must call parent::__construct() as follows:
   *
   * parent::__construct(
   * $this->get_widget_slug(),
   *   __( 'Widget Name', $this->get_widget_slug() ),
   *   array(
   *     'classname'  => $this->get_widget_slug().'-widget',
   *     'description' => __( 'The Widget Description', $this->get_widget_slug() )
   *   )
   * );
   */
  public function __construct();

  /*--------------------------------------------------*/
  /* Widget API Functions
  /*--------------------------------------------------*/

  /**
   * Outputs the content of the widget
   *
   * @param array $args The array of form elements
   * @param array $instance The current instance of the widget
   *
   * @return null
   */
  public function widget( $args, $instance );

  /**
   * Processes the widget's options to be saved
   *
   * @param array $new_instance The new instance of values to be generated via the update.
   * @param array $old_instance The previous instance of values before the update.
   *
   * @return array new_instance The new instance of values to be generated via the update.
   */
  public function update( $new_instance, $old_instance );

  /**
   * Generates the administration form for the widget
   *
   * @param array $instance The array of keys and values for the widget.
   *
   * @return null
   */
  public function form( $instance );

} // end interface