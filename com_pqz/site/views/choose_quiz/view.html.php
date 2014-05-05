<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class pqzViewchoose_quiz extends JViewLegacy {

    function display($tpl = null) {

        $this->items = $this->get('Items', 'choose_quiz');


        parent::display($tpl);
    }

}
