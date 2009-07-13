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
  }

  /**
   * The default task
   */
  function Display()
  {
    parent::display();
  }
}

