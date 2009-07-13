<?php
/**
 * Beursplein 5 default controller
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Beursplein 5 Component Controller
 */
class BeurspleinController extends JController
{
  /**
   * constructor (registers additional tasks to methods)
   * @return void
   */
  function __construct()
  {
    parent::__construct();

    // Register Extra tasks
    //$this->registerTask( 'add', 'edit' );
  }

  /**
   * The default task,
   * Add the required models and display the view
   */
  function Display()
  {
    $userModel      = JController::getModel("Users");
    
    //Get the models that all views need
    $stocksModel    = JController::getModel("Stocks");
    $portfolioModel = JController::getModel("Portfolio");
    $userModel      = JController::getModel("Users");
    
    //Checks for new user
    switch($userModel->checkUser())
    {
      case 0:
        $viewName = "newuser";
        break;
      case 1:
        $viewName = "newuser";
        break;
      case 2:
        //Get the viewname
        $viewName = JRequest::getVar('view', 'home');
        break;
      default:
        $viewName = "newuser";
        break;
    }
    
    //Get the view
    $view = JController::getView($viewName,'html');
    
    //Add the models every view needs
    $view->setModel($stocksModel);
    $view->setModel($portfolioModel);
    $view->setModel($userModel);
    
    //Some additional models
    if($viewName=="history")   $view->setModel(JController::getModel("History"));
    if($viewName=='dealcards') $view->setModel(JController::getModel("Cards"));
    if($viewName=='home')
    {
      $view->setModel(JController::getModel("Cards"));
      $view->setModel(JController::getModel("Log"));
    }
    
    //Display the view
    $view->display();
  }

  /**
   * Buys and sells all stocks that are posted
   */
  function buysell()
  {
    $stocks  = JRequest::getVar('stock'  , null, 'post');
    $options = JRequest::getVar('options', null, 'post');
    
    //If the message isn't set, display this
    $msg = "Error, message not set";
    $error = false;

    if(!is_array($stocks) || !is_array($options))
    {
      $msg = "Je hebt niks gekocht/verkocht";
      $error = false;
    }
    else
    {
      //Get the model
      $portfolioModel = JController::getModel("Portfolio");

      //Parse the stocks + options
      $stocks = $portfolioModel->parseStocks($stocks, $options, $msg);

      //If false, there was an error, message is already set
      if($stocks === false)
      {
        $error = true;
      }
      else
      {
        //Get the user's id
        $user = JFactory::getUser();
        $user_id = $user->id;

        //Get the user's money
        $userModel = JController::getModel("Users");
        $money = $userModel->getMoney($user_id);

        //Get the pricelist
        $stocksModel = JController::getModel("Stocks");
        $priceList = $stocksModel->getPriceList();

        //Calculate the total money needed for transaction
        foreach($stocks as $stock_id => $amount)
        {
	  $totalValue += $amount * $priceList[$stock_id];
        }
        
        //Money check
        if($totalValue>$money)
        {
          $msg = "Je hebt te weinig geld om die aandelen te kopen";
          $error = true;
        }
        else
        {
          //Voeg de aandelen toe
          if($portfolioModel->addStocks($user_id, $stocks, $msg))
          {
            //Get all stock info
            $stockInfo = $stocksModel->getStocksListTransformed();
            
            //Log everything
            $logModel = $this->getModel('Log');
            foreach($stocks as $stock_id => $amount)
            {
              $price = abs($amount * $stockInfo[$stock_id]['value']);
              
              if($amount > 0)
              {
                $text = "Buy: {$amount} x {$stockInfo[$stock_id]['name']} voor &euro;{$price}";
              }
              else
              {
                $amount = abs($amount);
                $text = "Sell: {$amount} x {$stockInfo[$stock_id]['name']} voor &euro;{$price}";
              }
              
              if(!$logModel->log($text, $user_id))
              {
                $error = true;
                $msg   = "Er was een error bij het wegschrijven van de log.";
              }
            }
            
            //Update the money
            if($userModel->setMoney($user_id, $money - $totalValue))
            {
              $result = $portfolioModel->deleteEmptyStocks();
              
              if($result === false)
              {
                $error = true;
                $msg   = "Can not delete old records!";
              }
              else
              {
                $error = false;
              }
            }
            else
            {
              $error = true;
              $msg = "Error, je geld is niet geupdated";
            }
          }
          else
          {
            $error = true;
          }
        }
      }
    }

    $link = "index.php?option=com_beursplein&view=stocks";

    if($error)
    {
      $this->setRedirect($link, $msg, 'error');
    }
    else
    {
      $this->setRedirect($link, $msg);
    }
  }  
  
  /**
   * Geeft de user nieuwe kaarten
   */
  function getcards()
  {
    //Get the cards in the deck
    $cardsModel = JController::getModel("Cards");
    $deck = $cardsModel->getDeckCards();    
    $deck = $cardsModel->sortCardsGroup($deck);
    
    //Some checks
    // 3 kaarten uit groep A
    // 2 kaarten uit groep B 
    // 5 kaarten uit groep C
        
    if(count($deck[1])<6)
      exit("Te weinig kaarten van soort 1");
    if(count($deck[1])<6)
      exit("Te weinig kaarten van soort 2");
    if(count($deck[1])<6)
      exit("Te weinig kaarten van soort 3");
    if(count($deck[1])<8)
      exit("Te weinig kaarten van soort 4");
    if(count($deck[1])<8)
      exit("Te weinig kaarten van soort 5");
    
    //Get random cards for each type
    //array_pick doen't work here, it would be better :)
    //Actually, with php it is almost impossible :)
    
    $dealedCards = array();
    
    foreach($deck as $group)
    {
      shuffle($group);
      $count = 0;
      
      foreach($group as $card)
      {
        if($card['group'] == 1)
          if($count == 3)
            break;
        if($card['group'] == 2)
          if($count == 2)
            break;
        if($card['group'] == 3)
          if($count == 5)
            break;
        $dealedCards[] = $card;
        $count++;
      }
    }
    
    $user = JFactory::getUser();
    $id   = $user->id;
    $db   = JFactory::getDBO();    
    
    foreach($dealedCards as $card)
    {
      $tableCard     = new stdClass;
      $tableCard->id = $card['id'];
      $tableCard->user_id = $id;
      
      if (!$db->updateObject( '#__beursplein_cards', $tableCard, 'id' )) {
        exit($db->stderr());
      }
    }
    
    $link = "index.php?option=com_beursplein&view=home";
    $msg  = "Je hebt nieuwe kaarten gekregen";
    $this->setRedirect($link, $msg);
  }
  
  /**
   * Select the card so next reset, it gets played
   */
  function selectcard()
  {
    //If msg is not set, display this
    $error = true;
    $msg   = "No msg set!";    
    
    //Expect an int
    $cardID = JRequest::getInt('card'  , 0, 'post');
    
    if($cardID == 0)
    {
      $error = true;
      $msg   = "Error, geen kaart geselecteerd";
    }
    else
    {
      //Look if that card is owned
      
      //First get the card
      $cardsModel = JController::getModel("Cards");
      $card       = $cardsModel->getCard($cardID);
      
      //Get the user_id
      $user    = JFactory::getUser();
      $user_id = $user->id;
      
      //Check if you own this card
      if($card['user_id'] != $user_id)
      {
        $error = true;
        $msg   = "Die kaart is niet van jou!";
      }
      else
      {
        //Check if card is not already played
        if($card['status'] != 'deck')
        {
          $msg   = "Error, die kaart is al gespeeld";
          $error = true;
        }
        else
        {
          $userModel = $this->getModel('Users');
          $error = !$userModel->selectCard($cardID, $user_id, $msg);
        }
      }
    }
    
    if(!$error)
    {
      $stockID = JRequest::getInt('stock'  , 0, 'post');
      
      if($stockID != 0)
      {
        $stocksModel = $this->getModel('Stocks');
        $stock = $stocksModel->getStock($stockID);
        
        if(!is_array($stock))
        {
          $error = true;
          $msg   = "Dat aandeel bestaat niet";
        }
        else
        {
          $userModel = $this->getModel('Users');
          $error = !$userModel->selectStock($stockID, $user_id, $msg);
        }
      }
    }
    
    $link = "index.php?option=com_beursplein&view=home";
    
    if($error)
    {
      $this->setRedirect($link, $msg, 'error');
    }
    else
    {
      $this->setRedirect($link, $msg);
    }
  }
  
  function register()
  {
    $userModel = JController::getModel("Users");
    $result = $userModel->registerUser();
    
    if($result)
    {
      $msg = "Het is gelukt, je kunt nu meespelen met beursplein 5";
    }
    else
    {
      $msg = "Er trad een error op. Misschien ben je nog niet ingelogd.";
    }
    
    $link = "index.php?option=com_beursplein&view=home";
    
    if(!$result)
    {
      $this->setRedirect($link, $msg, 'error');
    }
    else
    {
      $this->setRedirect($link, $msg);
    }
  }
}

