<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');
 
/**
 * Joomla to Grav Component Controller
 *
 */
class J2gravController extends JControllerLegacy
{
    /**
     * Method to display the view
     *
     * @access    public
     */
	public function display($cachable = false, $urlparams = false)
    {
        parent::display();
    }
 
}
?>