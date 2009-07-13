<?php
/**
* Beurspleins Model for Beursplein 5 Component
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * Beurspleins Cards Model
 */
class BeurspleinsModelCards extends JModel
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
  
  /*
   * Get all cards owned by user_id
   * If null, selects current user
   * @return The cards as AssocList
   */
  function getUserCards($id = null)
  {
    if($id===null)
    {
     $user = JFactory::getUser();
     $id   = $user->id;
    }
    
    $db = JFactory::getDBO();
    $q = "SELECT * 
          FROM  ".$db->nameQuote('#__beursplein_cards')."
          WHERE ".$db->nameQuote('user_id')." = ".$db->quote($id);
    $db->setQuery($q);
    
    return $db->loadAssocList();
  }
  
  /*
    * Get all cards not owned by anyone (thus in deck)
    * @return The cards as AssocList
    */
  function getDeckCards()
  {
    $db = JFactory::getDBO();
    $q = "SELECT * 
          FROM ".$db->nameQuote('#__beursplein_cards')."
          WHERE ".$db->nameQuote('user_id')." IS NULL";
    $db->setQuery($q);
    
    return $db->loadAssocList();
  }
  
  /*
    * Gets the card with that id
    * @return The card as Assoc
    */
  function getCard($id)
  {
    $db = JFactory::getDBO();
    $q = "SELECT * 
          FROM ".$db->nameQuote('#__beursplein_cards')."
          WHERE ".$db->nameQuote('id')." = ".$db->quote($id);
    $db->setQuery($q);
    
    return $db->loadAssoc();
  }
  
  /*
   * Sorts the cards given by $deck per type
   * @return array($type => array(cards));
   */
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
  
  /*
   * Sorts the cards given by $deck per group
   * @return array($group => array(cards));
   */
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

