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
    $this->updateCanSell();
    $this->updateStocks();
    
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
  
  function updateStocks()
  { 
    $this->disp("<br /><h4>Updating Stocks</h4>");
    
    //Process the cards...
    
    $db = JFactory::getDBO();
    
    $q = "SELECT * FROM `#__beursplein_stocks`";
    $db->setQuery($q);
    $stockList = $db->loadAssocList();
    
    //TODO
    //Process the cards
    //
    
    //Add some random
        foreach($stockList as $stock)
    {
      $old_value = $stock['value'];
      
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
      
      if($stock['value']<10)
      {
        $stock['value'] = 10;
      }
      if($stock['value']>300)
      {
        $stock['value'] = 300;
      }
      
      $change = $stock['value'] - $old_value;
      
      $entry = new stdClass;
      $entry->value = $stock['value'];
      $entry->change = $change;
      $entry->speed = $stock['speed'];
      $entry->growing = $stock['growing'];
      $entry->id = $stock['id'];
      
      $result = $db->updateObject('#__beursplein_stocks', $entry, 'id');
      echo $db->getQuery();
      $this->dispResult($result);
      
      /*
      $this->q("UPDATE `jos_beursplein_stocks`
        SET `value` = '{$stock['value']}',
        `change` = '{$change}',
        `speed` = '{$stock['speed']}',
        `growing` = '{$stock['growing']}'
        WHERE `id` ='{$stock['id']}' LIMIT 1");*/
      
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
