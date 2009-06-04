<?php
/**
 * Beursplein Model for Beursplein 5 Component
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * Beursplein Model
 */
class BeurspleinModelUsers extends JModel
{
  /**
   * Gets the money of a peron
   * @return an int with the money
   */
  function getMoney($id = null)
  {
    if($id == null)
    {
      $user = JFactory::getUser();
      $id = $user->id;
    }    
    
    $db =& JFactory::getDBO();
    $query = "SELECT money FROM #__beursplein_users WHERE id=".$db->Quote("$id");
    $db->setQuery( $query );
    $db->query();
    if($db->getNumRows()!=1)
    {
      jexit("User not found");
    }  
    
    return $db->loadResult();
  }
  
  /**
  * Sets the money of a user to an amount
  * @return Success?
  */
  function setMoney($id, $money)
  {
    $db = JFactory::getDBO();
    
    $user = new stdClass;
    $user->id    = $id;
    $user->money = $money;
    
    //void updateObject  (string $table, object &$object, string $keyName, [boolean $updateNulls = true]) 
    if (!$db->updateObject( '#__beursplein_users', $user, 'id' ))
    {
      return false;
    }
    else
    {
      return true;
    } 
  }   
}
