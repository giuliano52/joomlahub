<?php

/*
 * Verifica che se le domande di un file csv sono presenti:
 * utilizzo:
 * check-input-filename.php?filename=NOMEFILE
 */





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
