<?php

/*
 * Verifica che se le domande di un file csv sono presenti:
 * utilizzo:
 * check-input-filename.php?filename=NOMEFILE
 */



require_once('../common.lib.php');

require_once('../csv_gd.class.php');



$filename = isset($_GET['filename']) ? filter_input(INPUT_GET, 'filename', FILTER_SANITIZE_ENCODED) : "";

if ($filename == "") {
	die( "usage: check-confratulation-file.php?filename=animali1.csv");
}

$filename_completo = __DIR__."/../../../../../datahub/pqz/conf/$filename";

$data_filename_obj = new csv_gd($filename_completo);
$data_filename_orig = $data_filename_obj->csv_to_array();

foreach($data_filename_orig as $line) {
	echo '<pre>'.$line['url'].'</pre>';
	echo '<img src="'.$line['url'].'">';
}


echo "<hr>DONE";



