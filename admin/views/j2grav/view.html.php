<?php
/**
 * @version     0.2.0
 * @package     com_j2grav
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU/GPL
 * @author      Hugo Avila
 */
 
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 *
 * @package    HelloWorld
 */
 
class J2gravViewJ2grav extends JViewLegacy
{
    function display($tpl = null)
    {
		if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

        parent::display($tpl);
    }
}