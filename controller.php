<?php
/**
 * Beursplein Controller for Beursplein 5 Component
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

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
   * The default task, so display home
   */
  function Display()
  {
    //Get the models
    $stocksModel    = JController::getModel("Stocks");
    $portfolioModel = JController::getModel("Portfolio");
    $userModel      = JController::getModel("Users");
    $cardsModel     = JController::getModel("Cards");

    //Get the view
    $viewName = JRequest::getVar('view');
    $view     = JController::getView($viewName,'html');

    //Add the models to the view
    $view->setModel($stocksModel);
    $view->setModel($portfolioModel);
    $view->setModel($userModel);

    if($viewName=="history")   $view->setModel(JController::getModel("History"));
    if($viewName=='dealcards') $view->setModel(JController::getModel("Cards"));
    if($viewName=='home')      $view->setModel(JController::getModel("Cards"));

    //Display it
    $view->display();
  }

  /**
   * display the current values of the stocks
   * @return stocks array
   */
  function buysell()
  {
    $stocks  = JRequest::getVar('stock'  , 'default value goes here', 'post');
    $options = JRequest::getVar('options', 'default value goes here', 'post');

    $msg = "Error, message not set";
    $error = false;

    if(!is_array($stocks)||!is_array($options))
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

        //Check if money is enough
        foreach($stocks as $stock_id => $amount)
        {
	        $totalValue += $amount * $priceList[$stock_id];
        }
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
            //Update the money
	    if($userModel->setMoney($user_id, $money - $totalValue))
	    {
	      $error = false;
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
  
  /*
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
    
    $error = false;
    $msg   = "Je hebt nieuwe kaarten gekregen";

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
   * save a record (and redirect to main page)
   * @return void
   *
  function playcard()
  {
    //$model = $this->getModel('hello');
    //
    //if ($model->store($post)) {
    //  $msg = JText::_( 'Greeting Saved!' );
    //} else {
    //  $msg = JText::_( 'Error Saving Greeting' );
    //}

    // Check the table in so it can be edited.... we are done with it anyway
    //$link = 'index.php?option=com_hello';
    //$this->setRedirect($link, $msg);
  }

  /**
   * remove record(s)
   * @return void
   *
  function remove()
  {
    $model = $this->getModel('hello');
    if(!$model->delete()) {
      $msg = JText::_( 'Error: One or More Greetings Could not be Deleted' );
    } else {
      $msg = JText::_( 'Greeting(s) Deleted' );
    }

    $this->setRedirect( 'index.php?option=com_hello', $msg );
  }*/

  /**
   * cancel editing a record
   * @return void
   *
  function cancel()
  {
    $msg = JText::_( 'Operation Cancelled' );
    $this->setRedirect( 'index.php?option=com_hello', $msg );
  }*/
}

