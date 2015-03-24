<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class pqzViewpqz extends JViewLegacy {

    function display($tpl = null) {
        // default view ... redirect to chioose_quiz
        $app = & JFactory::getApplication();
        $app->redirect('index.php?option=com_pqz&view=choose_quiz');
        // parent::display($tpl);
    }

}
