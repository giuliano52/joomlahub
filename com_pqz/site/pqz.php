<?php

/*
 * Version 20140505
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');


require_once JPATH_COMPONENT.'/lib/pqz.class.php';
require_once JPATH_COMPONENT.'/lib/csv_gd.class.php';
require_once JPATH_COMPONENT.'/lib/common.lib.php';




jimport('joomla.application.component.controller');

$controller = JControllerLegacy::getInstance('pqz');


$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));


// Redirect if set by the controller
$controller->redirect();


/*
 * TODO
 * 
 */