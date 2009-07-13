<?php
/**
 * Beursplein 5 default controller
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Beursplein 5 Component Controller
 */
class BeurspleinsController extends JController
{
  /**
   * constructor (registers additional tasks to methods)
   * @return void
   */
  function __construct()
  {
    parent::__construct();

    // Register Extra tasks
    //$this->registerTask( 'add', 'edit' );
  }

  /**
   * The default task,
   * Add the required models and display the view
   */
  function Display()
  {
    parent::display();
  }
}
