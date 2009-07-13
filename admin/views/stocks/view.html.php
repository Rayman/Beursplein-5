<?php
/**
 * Beurspleins View for Beursplein 5 Component
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 * Beurspleins View
 */
class BeurspleinsViewStocks extends JView
{
  /**
   * Stocks view display method
   * @return void
   **/
  function display($tpl = null)
  {
    JToolBarHelper::title(   JText::_( 'Aandelen Manager' ), 'generic.png' );
    JToolBarHelper::deleteList();
    JToolBarHelper::editListX();
    JToolBarHelper::addNewX();
        
    // Get data from the model
    $items = & $this->get('Data');
    $this->assignRef('items', $items);
    
    parent::display($tpl);
  }
}

