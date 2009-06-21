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
class BeurspleinModelPortfolio extends JModel
{
  /**
   * Gets the stocks in a person's portfolio
   * @return array with stocks + values
   */
  
  function getStocksList($id = null)
  {
    if($id == null)
    {
      $user = JFactory::getUser();
      $id = $user->id;
    }
    
    $db =& JFactory::getDBO();    
    $query = "SELECT * 
              FROM ".$db->nameQuote('#__beursplein_portfolio')."
              WHERE ".$db->nameQuote('owner')." = ".$db->quote($id);
    $db->setQuery( $query );
    return $db->loadAssocList();
  }
  
  /**
  * Gets the stocks in a person's portfolio and adds the amounts together
  * @return array(stocks_id => amount)
  */
  function getStocksListTransformed($id = null)
  {
    if($id == null)
    {
      $user = JFactory::getUser();
      $id = $user->id;
    }
    
    //Get the user's stocks
    $list = $this->getStocksList($id);
    
    //The return list
    $stockList = array();
    
    foreach($list as $stock)
    {
      if(isset($stockList[$stock['id']]))
      {
        $stockList[$stock['id']]['amount'] += $stock['amount'];
      }
      else
      {
        $stockList[$stock['id']] = $stock;
      }
    }
    return $stockList;
  }
  
  /**
  * Gets all Stocks and add's the amounts together
  * @return array(stock_id => value)
  */
  function getTotalStocks()
  {
    $db =& JFactory::getDBO();
    
    $query = "SELECT * FROM #__beursplein_portfolio";
    $db->setQuery( $query );
    $list = $db->loadAssocList();
    
    //The return list
    $stockList = array();
    
    foreach($list as $stock)
    {
      if(isset($stockList[$stock['stock_id']]))
      {
        $stockList[$stock['stock_id']]['amount'] += $stock['amount'];
      }
      else
      {
        $stockList[$stock['stock_id']] = $stock;
      }
    }
    
    return $stockList;
  }  
  
  /**
   * Add a stocks to a user (array with stock_id=> amount, return message, error)
   * @return if success
   */
  function addStocks($user_id, $stocks, &$msg)
  {
    //Add the stock to the table
    foreach($stocks as $stock_id => $amount)
    {
      if(!$this->addStock($user_id, $stock_id, $amount, $msg))
      {
        if(!isset($msg))
        {
          $db =& JFactory::getDBO();
          $msg = "Er was een error: '".$db->getErrorMsg()."', '".$db->getQuery()."'";
          return false;
        }
        else
        {
          return false;
        }
      }
    }
    
    $msg = "Je hebt succesvol aandelen gekocht/verkocht!";
    return true;
  }
  
  /**
   * Add one stock to a user
   * @return bool if success
   */
  function addStock($user_id, $stock_id, $amount, &$msg)
  {
    if($amount>0) // BUY
    {
      //Query for just bought stocks
      $db =& JFactory::getDBO();
      $query = "SELECT ".$db->nameQuote('id').", ".$db->nameQuote('amount')."
                FROM   ".$db->nameQuote('#__beursplein_portfolio')."
                WHERE  ".$db->nameQuote('owner')."    = ".$db->quote($user_id)."
                AND    ".$db->nameQuote('stock_id')." = ".$db->quote($stock_id)."
                AND    ".$db->nameQuote('can_sell')." = ".$db->quote(2);
      $db->setQuery( $query );
      $db->query();
      
      $numrows = $db->getNumRows();
      
      if($numrows==0) //Case 1: Create a new entry
      {
        $stock = new stdClass;
        $stock->owner    = $user_id;
        $stock->stock_id = $stock_id;
        $stock->amount   = $amount;
        $stock->can_sell = 2;
        
        if(!$db->insertObject('#__beursplein_portfolio', $stock))
        {
          return false;
        }
      }
      elseif($numrows==1) //Case 2: Update it
      {
        $row = $db->loadAssoc();
        $stock = new stdClass;
        $stock->id     = $row['id'];
        $stock->amount = $row['amount'] + $amount;
        
        if(false === $db->updateObject('#__beursplein_portfolio', $stock, 'id' ))
        {
          return false;
        }
      }
      else
      {
        jexit("Corrupt database!!!");
      }
    }
    elseif($amount<0) //SELL
    {
      //Query for stocks bought a long time ago
      $db =& JFactory::getDBO();
      $query = "SELECT ".$db->nameQuote('id').", ".$db->nameQuote('amount')."
                FROM   ".$db->nameQuote('#__beursplein_portfolio')."
                WHERE  ".$db->nameQuote('owner')."    = ".$db->quote($user_id)." 
                AND    ".$db->nameQuote('stock_id')." = ".$db->quote($stock_id)."
                AND    ".$db->nameQuote('can_sell')." = ".$db->quote(0);
      $db->setQuery( $query );
      $db->query();
      
      $numrows = $db->getNumRows();
      
      if($numrows==0)
      {
        $msg = "Je hebt geen aandelen van id='{$stock_id}' om te verkopen";
        return false;
      }
      else
      {
        $row = $db->loadAssoc();
        
        if($row['amount'] < - $amount) //Amount is negative
        {
          $msg = "Je kunt maximaal {$row['amount']} aandelen met id='{$stock_id}' verkopen";
          return false;
        }  
        
        $stock = new stdClass;
        $stock->id     = $row['id'];
        $stock->amount = $row['amount'] + $amount; //Amount is negative
        
        if(false === $db->updateObject( '#__beursplein_portfolio', $stock, 'id' ))
        {
          return false;
        }
      }
    }
    
    
    
    return true;
  }
  
  /**
   * Parses 2 html-post variables and checks them on errors
   * @return array with stocks + amounts, false on error
   */
  function parseStocks($stocks, $options, &$msg)
  {
    //The return array
    $returnStocks = array();
    
    foreach($stocks as $id => $value)
    {
      //We expect an int
      $id    = (int)$id;
      $value = (int)$value;
      
      if($value == 0) //Stock didn't change, so we don't do anything with it
      {
        continue;
      }
      
      if($value < 0) //Error
      {
        $msg = "Geen negatieve getallen plx";
        return false;
      }    
      
      if(!isset($options[$id])) //Error, option not set
      {
        $msg = "Error, je hebt een optie niet aangevinkt. 
          ID: '{$id}', VALUE: '{$value}'";
        return false;
      }
      
      if($options[$id] == "Buy")
      {
        $amount = $value;
      }
      elseif($options[$id]=="Sell")
      {
        $amount = - $value;
      }
      else
      {
        $msg = "Error, wrong option";
        return false;
      }
      
      if($id == 0)
      {
        $msg = "Error, wrong ID";
        return false;
      }        
      
      $returnStocks[$id] = $amount;
    }
    
    return $returnStocks;
  }
  
  /*
   * Deletes all stocks with amount == 0 from db
   */
  function deleteEmptyStocks()
  {
    $db = JFactory::getDBO();
    $q  = "DELETE
           FROM ".$db->nameQuote('#__beursplein_portfolio')." 
           WHERE ".$db->nameQuote('amount')." = ".$db->quote(0);
    $db->setQuery;
    return $db->query();
  }
}

