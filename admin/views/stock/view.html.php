<?php
/**
 * Stock View for Beursplein 5 Component
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 * Stock View
 */
class BeurspleinsViewStock extends JView
{
  /**
   * display method of Stock view
   * @return void
   **/
  function display($tpl = null)
  {
    //get the stock
    $stock    =& $this->get('Data');
    $isNew    = ($stock->id < 1);

    $text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
    JToolBarHelper::title(   JText::_( 'Stock' ).': <small><small>[ ' . $text.' ]</small></small>' );
    JToolBarHelper::save();
    if ($isNew)  {
      JToolBarHelper::cancel();
    } else {
      // for existing items the button is renamed `close`
      JToolBarHelper::cancel( 'cancel', 'Close' );
    }

    $this->assignRef('stock',    $stock);

    parent::display($tpl);
  }
}