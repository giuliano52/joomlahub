<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class pqzViewchoose_quiz extends JViewLegacy {

    function display($tpl = null) {

        $this->items = $this->get('Items', 'choose_quiz');
		
		$data_xml = simplexml_load_file(JPATH_ADMINISTRATOR .'/components/com_pqz/pqz.xml');
		
		$this->data['version'] = $data_xml->version;
		

        parent::display($tpl);
    }

}
