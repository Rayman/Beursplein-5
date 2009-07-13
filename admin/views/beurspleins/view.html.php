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
class BeurspleinsViewBeurspleins extends JView
{
	/**
	 * Beurspleins view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('Beursplein 5 Manager'), 'generic.png');

		parent::display($tpl);
	}
}

