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
class BeurspleinsModelLog extends JModel
{
  /**
   * Logs the text to a users log
   * If user is omitted (or null), it logs it to all persons
   * If date is omitted (or null), it uses current date
   * @return if success
   */
  function log($text, $user = null, $date = null)
  {
    $db = JFactory::getDBO();
    
    if(is_null($date))
    {
      if(is_null($user))
      {
        $user = "NULL";
      }
      else
      {
        $user = $db->quote($user);
      }
      
      $q = "INSERT 
            INTO `#__beursplein_log`
            ( 
              ".$db->nameQuote('user_id')." , 
              ".$db->nameQuote('date')." , 
              ".$db->nameQuote('text')." 
            )
            VALUES 
            ( 
              ".$user." ,
              NOW( ) ,
              ".$db->quote($text)."
            )";
    }
    else
    {
      exit('Error, not supported');
    }
    
    $db->setQuery($q);
    echo $db->getQuery();
    
    return $db->query();
  }
  
  /*
   * Empies the #__beursplein_log table
   */
  function reset()
  {
    $db = $this->_db;
    $q = "TRUNCATE TABLE ".$db->nameQuote('jos_beursplein_log');
    $db->setQuery($q);
    echo $db->getQuery();
    
    return $db->query();
  }
}

