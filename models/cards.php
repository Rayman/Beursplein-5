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
class BeurspleinModelCards extends JModel
{
  /**
   * Adds a card to the db table
   * Images must be a array with urls to images, 4 of them plx
   * @return if success
   */
  function insertCard($type, $group, $stock_id, $images, $owner = false)
  {
    if(count($images)!=4)
    {
      exit("There must be 4 images for a card");
    }

    $card           = new stdClass;
    $card->type     = $type;
    $card->group    = $group;
    $card->stock_id = $stock_id;
    $card->images   = implode(",", $images);

    if($owner!==false)
      $card->user_id = $owner;

    $db =& JFactory::getDBO();

    if($db->insertObject("#__beursplein_cards", $card))
    {
      return true;
    }
    else
    {
      exit("Error: " . $db->stderr());
    }
  }
  
  function getUserCards($id = null)
  {
    if($id===null)
    {
     $user = JFactory::getUser();
     $id   = $user->id;
    }
    $q = "SELECT * FROM `#__beursplein_cards` WHERE `user_id` = '$id'";
    $db = JFactory::getDBO();
    $db->setQuery($q);
    
    return $db->loadAssocList();
  }
  
  function getDeckCards()
  {
    $q = "SELECT * FROM `#__beursplein_cards` WHERE `user_id` IS NULL";
    $db = JFactory::getDBO();
    $db->setQuery($q);
    
    return $db->loadAssocList();
  }
  
  function sortCardsType($deck)
  {
    $sortedDeck = Array();
    
   foreach($deck as $card)
   {
     if(isset($sortedDeck[$card['type']]))
     {
       $sortedDeck[$card['type']][] = $card;
     }
     else
     {
       $sortedDeck[$card['type']] = array();
       $sortedDeck[$card['type']][] = $card;
     } 
   }
   return $sortedDeck;
  }
  
  function sortCardsGroup($deck)
  {
    $sortedDeck = Array();
    
    foreach($deck as $card)
    {
      if(isset($sortedDeck[$card['group']]))
      {
        $sortedDeck[$card['group']][] = $card;
      }
      else
      {
        $sortedDeck[$card['group']] = array();
        $sortedDeck[$card['group']][] = $card;
      } 
    }
    return $sortedDeck;
  }
}