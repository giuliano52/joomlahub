<?php

class PqzModelchoose_quiz extends JModelItem {

    protected $Items;

    public function getItems() {

        $jinput = JFactory::getApplication()->input;
        $quiz_dir = $jinput->get('quiz_dir', '', 'string');
        return $this->choose_quiz($quiz_dir);
    }

    public function choose_quiz($quiz_dir = '') {

        $quiz = new pqz();
        $quiz->debug=false;
        $base_datahub = JPATH_COMPONENT . '/../../../datahub/pqz/';
        $quiz->configuration['base_data_dir'] = $base_datahub;
        $quiz->configuration['base_ini_dir'] = $base_datahub . 'ini/prod';
     //   $quiz->configuration['base_ini_dir'] = $base_datahub . 'ini/test';

        $scan_dir = $quiz->scanIniDir($quiz_dir);
        $this->Items = $scan_dir;

        return $scan_dir;
    }

}
