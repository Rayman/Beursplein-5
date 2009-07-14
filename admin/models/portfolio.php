<?php
/**
 * Beurspleins Model for Beursplein 5 Component
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );


/**
 * Beurspleins Model
 */
class BeurspleinsModelPortfolio extends JModel
{
  /*
   * Empies the #__beursplein_portfolo table
   */
  function reset()
  {
    $db = $this->_db;
    $q = "TRUNCATE TABLE ".$db->nameQuote('jos_beursplein_portfolio');
    $db->setQuery($q);
    echo $db->getQuery();
    
    return $db->query();
  }
}

