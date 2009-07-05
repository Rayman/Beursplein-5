<?php
/**
 * Beursplein View for Beursplein 5 Component
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Beursplein 5 Component
 */
class BeurspleinViewCron extends JView
{
  function display($tpl = null)
  {
    //can_sell -= 1
    $this->updateCanSell();
    
    //Load all stocks
    $db = JFactory::getDBO();
    $q = "SELECT * FROM `#__beursplein_stocks`";
    $db->setQuery($q);
    $stockList = $db->loadAssocList('id');
    $backup    = $stockList;
    
    //Execute cards on the stocklist
    $removeList = $this->executeCards($stockList);
    
    //Add some random on the stocks that didn't change
    //Save the stocks
    $this->saveStocks($stockList, $backup);
    
    $this->removeCards($removeList);
    
    parent::display($tpl);
  }
  
  function updateCanSell()
  {
    $this->disp("<h4>Updating portfolio</h4>");
    
    $db = JFactory::getDBO();
    $q = "SELECT * 
          FROM `#__beursplein_portfolio` 
          WHERE `can_sell` = '1'";
    $db->setQuery($q);
    $this->disp($q);
    
    $todo = $db->loadAssocList();
    
    foreach($todo as $row)
    {
      //Search for same stock with can_sell == 0
      $q = "SELECT * 
            FROM  `jos_beursplein_portfolio` 
            WHERE `can_sell` = '0' 
            AND   `owner`    = '{$row['owner']}' 
            AND   `stock_id` = '{$row['stock_id']}'";
      $db->setQuery($q);
      $this->disp($q);
      
      $db->query();
      
      $rows = $db->getNumRows();
      
      if($rows==0) //Not in DB, so normally update
      {
        $stock = new stdClass;
        $stock->can_sell = 0;
        $stock->id       = $row['id'];
        
        $result = $db->updateObject('#__beursplein_portfolio', $stock, 'id');
        
        /*
        $q = "UPDATE `jos_beursplein_portfolio` 
            SET `can_sell` = '0' 
            WHERE `id` ={$row['id']} 
            LIMIT 1";
        $db->setQuery($q);*/
        echo $db->getQuery();
        $this->dispresult($result);
      }
      elseif($rows==1) //Already in DB, so add amounts
      {
        $row2 = $db->loadAssoc();
        //Update the old
        $stock = new stdClass;
        $stock->amount = $row2['amount'] + $row['amount'];
        $stock->can_sell = 0;
        $stock->id       = $row2['id'];
        
        $result = $db->updateObject('#__beursplein_portfolio', $stock, 'id');
        echo $db->getQuery();
        $this->dispresult($result);
            
            /*
        $this->q("UPDATE `jos_beursplein_portfolio` 
            SET `amount` = `amount`+'{$row['amount']}', ".
            "`can_sell` = '0' WHERE `id` ={$row2['id']} LIMIT 1");*/
        
        //Delete the other
        $q = "DELETE 
              FROM `#__beursplein_portfolio`
              WHERE `id` = '{$row['id']}' 
              LIMIT 1";
        $db->setQuery($q);
        echo $db->getQuery();
        $this->dispresult($db->query());
      }
      else
      {
        exit("Database error, duplicate stocks!");
      }
    }
    
    $q = "UPDATE `#__beursplein_portfolio`
          SET `can_sell` = '1'
          WHERE `can_sell` = '2'";
    $db->setQuery($q);
    echo $q;
    
    $this->dispResult($db->query());
  }
  
  /*
   * Executes all selected cards
   * @return the list of id's of cards that have been executed
   */
  function executeCards(&$stockList)
  {
    $this->disp("<br /><h4>Updating Cards</h4>");
    
    $db = JFactory::getDBO();
    
    //Load all selected cards
    $q = "SELECT `id`, `stock_id`, `card_id` FROM `#__beursplein_users`";
    $db->setQuery($q);
    $this->disp($db->getQuery());
    $userList = $db->loadAssocList();
    
    //Load belonging cards
    $cardsList = array();
    foreach($userList as $user)
    {
      if(is_null($user['card_id']) || is_null($user['stock_id']))
        continue;
      
      $q = "SELECT * FROM `#__beursplein_cards` WHERE `id` = '{$user['card_id']}'";
      $db->setQuery($q);
      $this->disp($db->getQuery());
      
      $result = $db->loadAssoc();
      $cardsList[$result['id']] = $result;
    }
    
    //Load all stocks
    $backup    = $stockList;
    //List of cards to remove
    $removeList = array();
    
    foreach($userList as $user)
    {
      if(is_null($user['card_id']) || is_null($user['stock_id']))
        continue;
      
      $card = $cardsList[$user['card_id']];
      $this->disp("Executing card_id={$card['id']}, type={$card['type']}");
      if($card['user_id'] != $user['id'])
      {
        $this->disp("That card doesn't belong to you!!!");
        continue;
      }
      if($card['status'] != "deck")
      {
        $this->disp("That card is already played!!!");
      }
      
      switch ($card['type'])
      {
        case 1:
          //2x selected stock, 1/2 stock on card
          $stockList[$user['stock_id']]['value'] *= 2;
          $stockList[$card['stock_id']]['value'] /= 2;
          break;
        case 2:
          //2x stock on card, 1/2 on selected stock
          $stockList[$user['stock_id']]['value'] /= 2;
          $stockList[$card['stock_id']]['value'] *= 2;
          break;
        case 3:
          //+100 stock on card, -10 on all other stocks
          $stockList[$card['stock_id']]['value'] += 110;//Gets substracted below
          foreach($stockList as $temp)
            $stockList[$temp['id']]['value'] -= 10;
          break;
        case 4:
          //+40 on selected stock, -50 on stock on card
          $stockList[$user['stock_id']]['value'] += 40;
          $stockList[$card['stock_id']]['value'] -= 50;
          break;
        case 5:
          //+60 on stock on card -30 on selected stock
          $stockList[$card['stock_id']]['value'] += 60;
          $stockList[$user['stock_id']]['value'] -= 30;
          break;
        default:
          echo "That card type doesn't exist";
      }
      
      //Add the card to the removelist
      $removeList[] = $card['id'];
    }
    
    return $removeList;
  }
  
  function saveStocks(&$stockList, &$backup )
  { 
    $this->disp("<br /><h4>Updating Stocks</h4>");
    
    $db = JFactory::getDBO();
    
    //Enumerate trough all stocks
    foreach($stockList as $stock)
    {
      //Backup
      $old_value = $backup[$stock['id']]['value'];
      
      //Add some random
      if($old_value == $stock['value'])
      {
        $this->disp("Adding random to stock {$stock['name']}");
        if($stock['growing']=="true")
        {
          $stock['speed'] += rand(0,6);
          if($stock['speed']>=10)
          {
            $stock['growing']="false";
          }
        }
        else
        {
          $stock['speed'] -= rand(0,6);
          if($stock['speed']<=-10)
          {
            $stock['growing']="true";
          }
        }
        $stock['value'] += $stock['speed'] + rand(-6,6);
      }
      else
      {
        $this->disp("Stock {$stock['name']} changed already");
      }
      
      //Check for boundaries
      //TODO, give money if boundaries are crossed
      if($stock['value']<10)
      {
        //Substract money to users
        $this->disp("Stock below 10, money loss!!");        
        $diff = 10 - $stock['value'];
        
        $q = "SELECT * FROM `#__beursplein_portfolio` WHERE `stock_id` = '{$stock['id']}'";
        $db->setQuery($q);
        $this->disp($db->getQuery());
        $result = $db->loadAssocList();
        
        foreach($result as $entry)
        {
          $loss = $entry['amount'] * $diff;
          $q = "UPDATE `#__beursplein_users`
              SET `money` = `money` - '$loss'
              WHERE `id` = '{$entry['owner']}'";
          $db->setQuery($q);
          echo $db->getQuery();
          $this->dispResult($db->query());
        }
        
        $stock['value'] = 10;
      }
      if($stock['value']>300)
      {
        //Add money to users
        $diff = $stock['value'] - 300;
        
        $q = "SELECT * FROM `#__beursplein_portfolio` WHERE `stock_id` = '{$stock['id']}'";
        $db->setQuery($q);
        $this->disp($db->getQuery());
        $result = $db->loadAssocList();
        
        foreach($result as $entry)
        {
          $gain = $entry['amount'] * $diff;
          $q = "UPDATE `#__beursplein_users`
              SET `money` = `money` + '$gain'
              WHERE `id` = '{$entry['owner']}'";
          $db->setQuery($q);
          echo $db->getQuery();
          $this->dispResult($db->query());
        }
        
        $stock['value'] = 300;
      }
      
      $change = $stock['value'] - $old_value;
      
      $entry = new stdClass;
      $entry->value   = $stock['value'];
      $entry->change  = $change;
      $entry->speed   = $stock['speed'];
      $entry->growing = $stock['growing'];
      $entry->id      = $stock['id'];
      
      $result = $db->updateObject('#__beursplein_stocks', $entry, 'id');
      echo $db->getQuery();
      $this->dispResult($result);
      
      $this->logToHistory($stock);
      
      echo "<br />\r\n";
    }
  }
  
  function removeCards($cardList)
  {
    $this->disp("<h4>Removing all executed cards</h4>");
    $db = JFactory::getDBO();
    
    //Delete all cards
    foreach($cardList as $id)
    {
      $entry = new stdClass;
      $entry->status = 'played';
      $entry->id     = $id;
      
      $result = $db->updateObject('#__beursplein_cards', $entry, 'id');
      echo $db->getQuery();
      $this->dispResult($result);
    }
    
    //Set all selected cards to NULL
    $q = "UPDATE `#__beursplein_users`
          SET `card_id`  = NULL , 
              `stock_id` = NULL";
    $db->setQuery($q);
    echo $db->getQuery();
    $this->dispResult($db->query());
  } 
  
  function logToHistory($stock)
  {
    $db = JFactory::getDBO();
    $q = "SELECT `amount` 
          FROM `#__beursplein_portfolio` 
          WHERE `stock_id` = '{$stock['id']}'";
    $db->setQuery($q);
    $this->disp($db->getQuery());
    $amountList = $db->loadAssocList();
    
    //Add the amounts
        $sum = 0;
    foreach($amountList as $row)
      $sum += $row['amount'];
    
    $entry = new stdClass;
    $entry->stock_id = $stock['id'];
    $entry->value    = $stock['value'];
    $entry->volume   = $sum;
    
    $result = $db->insertObject('#__beursplein_history', $entry);
    /*
        $this->q("INSERT INTO `jos_beursplein_history` 
        (`stock_id` ,`value` ,`volume`) VALUES 
        ('{$stock['id']}', '{$stock['value']}', '{$sum}')");*/
        echo $db->getQuery();
    $this->dispResult($result);
  }
  
  
  function disp($s)
  {
    echo $s;
    echo "<br />\r\n";
  }
  
  function dispResult($result)
  {
    if($result === true)
    {
      echo " <b>OK</b><br />\r\n";
    }
    elseif($result === false)
    {
      echo " <b>Error</b><br />\r\n";
    }
    else
    {
      echo " <b>Error, not a bool</b><br />\r\n";
    }
  }
}
?>
