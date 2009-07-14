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
class BeurspleinsController extends JController
{
  /**
   * constructor (registers additional tasks to methods)
   * @return void
   */
  function __construct()
  {
    parent::__construct();
  }

  /**
   * The default task
   */
  function Display()
  {
    parent::display();
  }
  
  /**
   * reset the game
   */
  function reset()
  {
    //TODO, save the high-scores
    
    //Delete all users 
    $userModel = $this->getModel('Users');
    if($userModel->reset())
    {
      echo " <b>OK</b><br />";
    }
    else
    {
      exit(" ERROR");
    }
    
    //Reset all stocks
    $stocksModel = $this->getModel('Stocks');
    if(!$stocksModel->reset())
    {
      exit(" ERROR");
    }
    
    //Delete all entries in portfolio
    $portfolioModel = $this->getModel('Portfolio');
    if($portfolioModel->reset())
    {
      echo " <b>OK</b><br />";
    }
    else
    {
      exit(" ERROR");
    }
    
    //Delete all entries in history
    $historyModel = $this->getModel('History');
    if($historyModel->reset())
    {
      echo " <b>OK</b><br />";
    }
    else
    {
      exit(" ERROR");
    }
    
    //Delete all entries in history
    $logModel = $this->getModel('Log');
    if($logModel->reset())
    {
      echo " <b>OK</b><br />";
    }
    else
    {
      exit(" ERROR");
    }
    
    //Display the view where the cards are dealed
    $view = $this->getView('cards', 'html');
    
    $cardsModel = $this->getModel('Cards');
    $view->setModel($cardsModel);
    
    $view->display();
    
    $result = $logModel->log('*** HET SPEL IS OPNIEUW BEGONNEN!!! ***');
    if($result)
    {
      echo " <b>OK</b><br />";
    }
    else
    {
      exit(" ERROR");
    }
  }
}

