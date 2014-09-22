<?php

/*
 * Verifica che se le domande di un file csv sono presenti:
 * utilizzo:
 * check-input-filename.php?filename=NOMEFILE
 */

function local_emit_quiz($quiz, $id_question) {
    // mostra tutte le domande del quiz (compreso la parte html)

//print_pre($quiz);

    $csv_filename = $quiz->configuration['base_data_dir'] . '/csv/' . $quiz->configuration['csv_input'];

    $data_quiz_src_obj = new csv_gd($csv_filename);
    $data_quiz_src_orig = $data_quiz_src_obj->csv_to_array();
    
    print_pre($data_quiz_src_orig);
}

require_once('../../lib/common.lib.php');
require_once('../../lib/pqz.class.php');
require_once('../../lib/emit.php');

require_once('../csv_gd.class.php');


$task = isset($_GET['task']) ? filter_input(INPUT_GET, 'task', FILTER_SANITIZE_ENCODED) : "choose_quiz";
$quiz_dir = isset($_GET['quiz_dir']) ? filter_input(INPUT_GET, 'quiz_dir', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
$quiz_conf = isset($_GET['quiz_conf']) ? filter_input(INPUT_GET, 'quiz_conf', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
$id_question = isset($_GET['id_question']) ? filter_input(INPUT_GET, 'id_question', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';


$quiz = new pqz();
$base_img_path = '../../img';
$base_datahub = '../../../../../datahub/pqz/';
$quiz->configuration['base_data_dir'] = $base_datahub;
$quiz->configuration['base_ini_dir'] = $base_datahub . 'ini/prod';


switch ($task) {
    case 'choose_quiz':

        $scan_dir = $quiz->scanIniDir($quiz_dir);
        emit_choose_quiz($scan_dir);
        break;
    case 'start_quiz':

        $quiz->start_quiz($quiz_conf);
        local_emit_quiz($quiz, $id_question);

        break;
    default :
        die("TASK: <b>$task</b> unrecognized");
}

/*

if (isset($_REQUEST['filename'])) {
    $filename = $_REQUEST['filename'];
} else {
    $errore = "Usage: check-input-filename.php?filename=NOMEFILE "
            . "<br>"
            . "Esempio: <br>"
            . "AAA";
    die($errore);
}


require_once(__DIR__ . '/../csv_gd.class.php');



$data_quiz_src_obj = new csv_gd($filename);
$data_quiz_src_orig = $data_quiz_src_obj->csv_to_array();

print_r($data_quiz_src_orig);
*/