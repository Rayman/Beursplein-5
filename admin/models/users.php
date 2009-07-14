<?php
/**
 * Users Model for Beursplein 5 Component
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

/**
 * Users Model
 */
class BeurspleinsModelUsers extends JModel
{
  /**
   * Empies the users table
   * @return if success
   */
  function reset()
  {
    $db = $this->_db;
    $q = "TRUNCATE TABLE ".$db->nameQuote('jos_beursplein_users');
    $db->setQuery($q);
    echo $db->getQuery();
    
    return $db->query();
  }
}

