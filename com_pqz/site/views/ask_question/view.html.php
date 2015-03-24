<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class pqzViewask_question extends JViewLegacy {

    protected $question_index;
    protected $all_answered;

    function display($tpl = null) {

        $jinput = JFactory::getApplication()->input;
        $question_index = $jinput->get('id_question', '0', 'int');
        $this->question_index = $this->check_max_min_starting_question($question_index);
        $this->all_answered = $this->check_all_answer();
        
     
        
        parent::display($tpl);
    }

    private function check_max_min_starting_question($starting_question) {
        return min(max(0, $starting_question), $_SESSION['pqz_configuration']['max_question_total'] - 1);
    }

    private function check_all_answer() {
        $all_answer = true;
        foreach ($_SESSION['pqz_question'] as $question) {
            if (!isset($question['answered_question'])) {
                $all_answer = false;
            }
        }

        return $all_answer;
    }

}
