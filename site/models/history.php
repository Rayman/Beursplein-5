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
class BeurspleinModelHistory extends JModel
{
  function getHistory($stock_id, $limit = 20)
  {
    $limit = (int)$limit;
    $db = JFactory::getDBO();
    $q  = "SELECT * 
           FROM (
             SELECT * 
             FROM     ".$db->nameQuote('jos_beursplein_history')." 
             WHERE    ".$db->nameQuote('stock_id')." = ".$db->quote($stock_id)." 
             ORDER BY ".$db->nameQuote('id')." DESC 
             LIMIT 20 
           ) 
           AS tbl 
           ORDER BY tbl.`id`";
    /*
    $q  = "SELECT *
           FROM ".$db->nameQuote('#__beursplein_history')."
           WHERE ".$db->nameQuote('stock_id')." = ".$db->quote($stock_id)."
           LIMIT 0 , ".$limit;*/
    $db->setQuery($q);
    
    return $db->loadAssocList();
  }
}
