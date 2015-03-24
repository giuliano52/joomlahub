<?php
session_start();

/*
 * TODO: implementare il campo if_correct and if_wrong nei risultati
 */

$version = "3.20150323.01";
$debug = false;

$quiz_site = 'produzione';   	// visualizza i quiz di produzione
//$quiz_site = 'test'				// visualizza i quiz di test

?>

<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="lib/style.css" />
        <title>PQZ</title>
    </head> 
    <body>


        <?php
        // TODO : evitare l'escape ../../ sui nomi file (es riferire tutto dal path) 

        require_once('lib/common.lib.php');
        require_once('lib/pqz.class.php');
        require_once('lib/emit.php');

        $task = isset($_GET['task']) ? filter_input(INPUT_GET, 'task', FILTER_SANITIZE_ENCODED) : "choose_quiz";
        $quiz_dir = isset($_GET['quiz_dir']) ? filter_input(INPUT_GET, 'quiz_dir', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
        $quiz_conf = isset($_GET['quiz_conf']) ? filter_input(INPUT_GET, 'quiz_conf', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
        $id_question = isset($_GET['id_question']) ? filter_input(INPUT_GET, 'id_question', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';

        $base_img_path = 'img';
        $quiz = new pqz();
		$quiz->debug = $debug;
        $base_datahub = '../../../datahub/pqz/';
        $quiz->configuration['base_data_dir'] = $base_datahub;
        
		
		if ($quiz_site=='test') 
		{
			$quiz->configuration['base_ini_dir'] = $base_datahub . 'ini/test';
		}
		else {
			$quiz->configuration['base_ini_dir'] = $base_datahub . 'ini/prod';		
		}

		


        switch ($task) {
            case 'choose_quiz':
                $scan_dir = $quiz->scanIniDir($quiz_dir);
                emit_choose_quiz($scan_dir);
                break;
            case 'start_quiz':
                $quiz->start_quiz($quiz_conf);
                emit_questions($id_question);
                break;
            case 'ask_question':
                emit_questions($id_question);
                break;
            case 'show_results':
                emit_results();
                break;
            default :
                die("TASK: <b>$task</b> unrecognized");
        }
        ?>
        <br>
        <a href="<?= $_SERVER["PHP_SELF"] ?>">Scegli il Quiz</a>
        <br>
        <hr><?= $version ?><br>

    </body>

</html>