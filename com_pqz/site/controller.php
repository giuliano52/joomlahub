<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

class PqzController extends JControllerLegacy {

    public function display($cachable = false, $urlparams = false) {
        // default controller function
        $this->choose_quiz($cachable, $urlparams);
    }

    public function choose_quiz($cachable = false, $urlparams = false) {
        // "controller choose_quiz";

        $model = $this->getModel('choose_quiz');

        return parent::display($cachable, $urlparams);
    }

    public function start_quiz($cachable = false, $urlparams = false) {

        $jinput = JFactory::getApplication()->input;
        $quiz_conf = $jinput->get('quiz_conf', '', 'string');
        $model = $this->getModel('start_quiz');
        $model->start_quiz($quiz_conf);

        //set the view:
        $jinput->set('view', 'ask_question');

        return parent::display($cachable, $urlparams);
    }

    public function ask_question($cachable = false, $urlparams = false) {
        $jinput = JFactory::getApplication()->input;
        $model = $this->getModel('ask_question');

        $store_answer = $jinput->get('store_answer', 'false', 'boolean');

        if ($store_answer) {
            $model->store_answers();
        }

        $jinput->set('view', 'ask_question');
        return parent::display($cachable, $urlparams);
    }

    public function show_results($cachable = false, $urlparams = false) {

        $model = $this->getModel('show_results');
        $model->quiz_check_answer();

        $jinput = JFactory::getApplication()->input;
        $jinput->set('view', 'show_results');

        return parent::display($cachable, $urlparams);
    }

}
