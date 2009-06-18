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
    $this->disp("<h4>Updating portfolio</h4>");

    $q = "SELECT * FROM `jos_beursplein_portfolio` WHERE `can_sell` = 1";
    $result = $this->q($q);

    while($row = mysql_fetch_assoc($result))
    {
      //Search for same stock with can_sell == 0
      $r = $this->q("SELECT * FROM `jos_beursplein_portfolio` WHERE `can_sell`=0 AND ".
        "`owner`='{$row['owner']}' AND `stock_id`='{$row['stock_id']}'");
      $rows = mysql_num_rows($r);
      
      if($rows==0) //Not in DB, so normally update
      {
        $this->q("UPDATE `jos_beursplein_portfolio` SET `can_sell` = '0' WHERE `id` ={$row['id']} LIMIT 1");
      }
      elseif($rows==1) //Already in DB, so add amounts
      {
        $row2 = mysql_fetch_assoc($r);
        //Update the old
        $this->q("UPDATE `jos_beursplein_portfolio` SET `amount` = `amount`+'{$row['amount']}', ".
            "`can_sell` = '2' WHERE `id` ={$row2['id']} LIMIT 1");
        //Delete the other
        $this->q("DELETE FROM `jos_beursplein_portfolio` WHERE `id` = '{$row['id']}' LIMIT 1");
      }
      else
      {
        exit("Database error, duplicate stocks!");
      }
    }
    
    $this->disp("<h4>Updating Stocks</h4>");
    
    //Process the cards...
    $result = $this->q("SELECT * FROM `jos_beursplein_stocks`");
    $stocks = array();
    while($row = mysql_fetch_assoc($result))
    {
      $stocks[$row['id']] = $row;
    }
    
    //TODO
    //
    //
    
    //Add some random
    foreach($stocks as $id => $stock)
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
      $this->q("UPDATE `jos_beursplein_stocks`
        SET `value` = '{$stock['value']}',
        `change` = '{$change}',
        `speed` = '{$stock['speed']}',
        `growing` = '{$stock['growing']}'
        WHERE `id` ='{$stock['id']}' LIMIT 1");
      
      $r = $this->q("SELECT `amount` FROM `jos_beursplein_portfolio` WHERE `stock_id`='{$stock['id']}'");
      $sum = 0;
      while($row = mysql_fetch_assoc($r))
        $sum += $row['amount'];
      
      $this->q("INSERT INTO `jos_beursplein_history` 
        (`stock_id` ,`value` ,`volume`) VALUES 
        ('{$stock['id']}', '{$stock['value']}', '{$sum}')");
    }
    
    parent::display($tpl);
  }
  
  function disp($s){echo $s;echo "<br />\r\n";}
  function q($q)
  {
    $db = JFactory::getDBO();
    $db->setQuery($q);
    $result = $db->query();
    
    if($result===false)
    {
      $this->disp($q);
      exit("Could not successfully run query ($sql) from DB: " . mysql_error());
    }
    elseif($result===true) //Update statement
    {
      $affected = mysql_affected_rows();
      $this->disp($q." (OK, affected: {$affected})");
      return $result;
    }
    else //Select statement
    {
      $numrows = mysql_num_rows($result);
      $affected = mysql_affected_rows();
      $this->disp($q." (numrows: {$numrows}, affected: {$affected})");
      return $result;
    }
  }
}
?>
