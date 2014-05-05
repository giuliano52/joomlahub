<?php

class PqzModelstart_quiz extends JModelItem {

    public function start_quiz($quiz_conf) {
        $quiz = new pqz();
        $quiz->debug = false;
        $base_datahub = JPATH_COMPONENT . '/../../../datahub/pqz/';
        $quiz->configuration['base_data_dir'] = $base_datahub;
        $quiz->configuration['base_ini_dir'] = $base_datahub . 'ini/prod';


        $quiz->start_quiz($quiz_conf);
    }

}
