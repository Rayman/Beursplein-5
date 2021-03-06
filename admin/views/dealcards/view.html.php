<?php
/**
* Beurspleins View for Beursplein 5 Component
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
* HTML View class for the Beursplein 5 Component
*/
class BeurspleinsViewDealCards extends JView
{
  /**
   * Exectues a SQL command and displays error message and stuff
   */
  function q($q)
  {
    $db =& JFactory::getDBO();
    echo "<i>{$q}</i> ";
    $db->setQuery( $q );
    $result = $db->query();

    if($result===true)
    {
      echo "<b>OK</b>";
    }
    elseif($result===false)
    {
      $msg = $db->stderr();
      echo "<b> Error: '{$msg}'</b>";
    }
    else
    {
      echo "<b>OK</b>";
    }
    echo "<br />\n";
    return $db;
  }

  /**
   * Deletes the cards table and generates it
   * Usefull for when you just added a stock
   */
  function emptyCardsDB()
  {
    $query = 'TRUNCATE TABLE `#__beursplein_cards`';
    $this->q($query);
  }

  /**
   * For each stock in the db, generates all possible cards
   * and adds them to the db
   */
  function generateCards()
  {
    $query = "SELECT `id`, `image` FROM `#__beursplein_stocks`";
    $db = $this->q($query);
    $list = $db->loadAssocList();

    //Get the model from the cards table
    $cardsModel = $this->getModel("cards");
    
    foreach($list as $stock)
    {
      //6 x type 1
      //insertCard($type, $group, $stock_id, $images, $owner = false)

      echo "Inserting cards for stock_id={$stock['id']} ";
      
      $images = array(
        "media/com_beursplein/cards/2xup.gif",
        "media/com_beursplein/cards/1dot.gif",
        "media/com_beursplein/cards/12xdown.gif",
        $stock['image']
      );      

      for($i=0; $i<6; $i++)
      {
        echo $cardsModel->insertCard(1, 1, $stock['id'], $images)
          ? "." : " Error";
      }
      
      //6 x type 2
      
      $images = array(
        "media/com_beursplein/cards/2xup.gif",
        $stock['image'],
        "media/com_beursplein/cards/12xdown.gif",
        "media/com_beursplein/cards/1dot.gif"
      );
      
      for($i=0; $i<6; $i++)
      {
        echo $cardsModel->insertCard(2, 1, $stock['id'], $images)
          ? "." : " Error";
      }
      
      //6 x type 3
      $images = array(
        "media/com_beursplein/cards/100up.gif",
        $stock['image'],
        "media/com_beursplein/cards/10down.gif",
        "media/com_beursplein/cards/3dot.gif",
      );
      
      for($i=0; $i<6; $i++)
      {
        echo $cardsModel->insertCard(3, 2, $stock['id'], $images)
          ? "." : " Error";
      }      
      
      //8 x type 4
      $images = array(
        "media/com_beursplein/cards/40up.gif",
        "media/com_beursplein/cards/1dot.gif",
        "media/com_beursplein/cards/50down.gif",
        $stock['image']
      );
      
      for($i=0; $i<8; $i++)
      {
        echo $cardsModel->insertCard(4, 3, $stock['id'], $images)
          ? "." : " Error";
      }
      
      //8 x  type 5
      $images = array(
        "media/com_beursplein/cards/60up.gif",
        $stock['image'],
        "media/com_beursplein/cards/30down.gif",
        "media/com_beursplein/cards/1dot.gif"
      );
        
      for($i=0; $i<8; $i++)
      {
        echo $cardsModel->insertCard(5, 3, $stock['id'], $images)
          ? "." : " Error";
      }
      
      //DONE!
      echo " <b>OK</b><br />\n";
    }
  }

  function display($tpl = null)
  {
    $this->emptyCardsDB();
    $this->generateCards();

    parent::display($tpl);
  }
}
?>

