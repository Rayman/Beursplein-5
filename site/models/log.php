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
class BeurspleinModelLog extends JModel
{
  /**
   * Logs the text to a users log
   * If user is omitted (or null), it logs it to all persons
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
              ".$user." 
              ".$db->quote($user)." , 
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
  
  /**
   * Gets the shortlog for a person
   * @return string with html formatted text
   */
  function getShortlog($user_id = null, $limit = 15)
  {
    if($user_id == null)
    {
      $user    = JFactory::getUser();
      $user_id = $user->id;
    }
    
    $db = JFactory::getDBO();
    $q = "SELECT *
          FROM (
            SELECT *
            FROM `#__beursplein_log`
            WHERE `user_id` = ".$db->quote($user_id)."
            ORDER BY `id` DESC
            LIMIT $limit
          )
         AS `tmp`";
    $db->setQuery($q);
    $result = $db->loadAssocList();
    
    $html = "";
    foreach($result as $entry)
    {
      $date = strtotime($entry['date']);
      $diff = time() - $date;
      
      if($diff < 60 * 60)
      {
        $str = "".round($diff/60) . " minutes ago";
      }
      elseif($result < 60 * 60 * 24)
      {
        $str = "".round($diff/3600)." hours ago";
      }
      else
      {
        $str = "".round($diff/86400)." days ago";
      }
      
      
      $html .= "<b>".$str."</b> " . $entry['text'] . "<br />";
    }
    
    return $html;
  }
}