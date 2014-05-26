<?php

function emit_choose_quiz($scan_dir) {
    $option = isset($_REQUEST['option']) ? filter_input(INPUT_GET, 'option') : "";
    global $base_img_path;

    $content = "<table id=\"fancytable\" style=\"width:500px;\">\n";
    $content .="<tr><th>Type</th><th>Quiz Name</th><th>Options</th></tr>";
    $alt = false;


    foreach ($scan_dir as $item) {
        $type = $item['type'];
        $path = $item['path'];

        if ($alt) {
            $content .= "<tr>\n";
        } else {
            $content .= "<tr class=\"alt\">\n";
        }

        if ($type == "dir") {
            $entry = $item['name'];
            $content .= "<td><img src=\"$base_img_path/Blue_folder_seth_yastrov_01-64.png\" height=\"64\" width=\"64\" ></td>";
            $content .= "<td><A href=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "?option=$option&task=choose_quiz&quiz_dir=$path\">$entry</A></td>\n";
            $content .= "<td>&nbsp;</td>";
        } elseif ($type == "file") {
            $entry = $item['title'];

            $quiz_icon = isset($item['quiz_icon']) ? $item['quiz_icon'] : $base_img_path . '/quiz.png';
            $content .= "<td><img src=\"$quiz_icon\" alt=\"Quiz\" height=\"64\" width=\"64\"></td>";
            $content .= '<td><A href="' . htmlspecialchars($_SERVER["PHP_SELF"]) . "?option=$option&task=start_quiz&quiz_conf=$path\">$entry</A></td>\n";
            $content .= '<td>Avanzate -> TODO</td>';
//   $content .= '<td><A href="' . htmlspecialchars($_SERVER["PHP_SELF"]) . "?option=$option&task=choose_quiz_advanced&quiz_configuration=" . $path . '">Opzioni Avanzate</A></td>' . "\n";
        }
        $content .= "</tr>\n";
        $alt = !$alt;
    }
    $content .= "</table>\n";
    echo $content;
//return $content;
}

function emit_start_quiz() {
    $option = isset($_REQUEST['option']) ? filter_input(INPUT_GET, 'option') : "";
    $php_self = htmlspecialchars($_SERVER["PHP_SELF"]);
    $content = "<a href=\"$php_self?option=$option&task=ask_question&id_question=0\" >Inizia il quiz</a>\n";

    echo $content;
}

function emit_questions($starting_question = 0) {

    $starting_question = check_max_min_starting_question($starting_question);
    $option = isset($_REQUEST['option']) ? filter_input(INPUT_GET, 'option') : "";
//$store_answer = isset($_REQUEST['store_answer']) ? filter_input(INPUT_POST, 'store_answer') : "false";
    $store_answer = isset($_REQUEST['store_answer']) ? $_REQUEST['store_answer'] : "false";


    if ($store_answer == 'true') {
        store_answers();
    }

    echo '
<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="get">
<table border="1">
';
    emit_single_question($starting_question);
    $next_question = $starting_question + 1;
    echo '</table>';
    echo '<input type="hidden" name="id_question" value="' . $next_question . '"> ';
    echo '<input type="hidden" name="task" value="ask_question"> ';
    echo '<input type="hidden" name="store_answer" value="true"> ';
    echo '<input type="hidden" name="option" value="' . $option . '"> ';
    $all_answered = check_all_answer();
    if ($all_answered == TRUE) {
        echo "<br /><br />\n";
        echo "Hai risposto a tutte le domande: Guarda i ";
        echo '<b><a href="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?task=show_results&option=' . $option . '">RISULTATI</a></b>';
    } else {
        echo "\n";
//	echo '<input type="submit" value="Precedente" Name="Nav">';
        echo '<input type="submit" value="Prossimo" Name="Nav">';
    }
    echo "</form>\n";
}

function emit_single_question($question_index) {
// emit code for a single question

    $single_quiz_data = $_SESSION['pqz_question'][$question_index];

    $answer = isset($single_quiz_data['answered_question']) ? $single_quiz_data['answered_question'] : "";
    echo "<tr><td>\n";
    echo emit_question_status($question_index);
    echo "</td>\n";
    echo "<td>\n";
    echo $single_quiz_data["question"];
    echo "</td>\n";
    echo "<td class=\"possible_answer\">\n";
    $response_type = isset($single_quiz_data['response_type']) ? $single_quiz_data['response_type'] : $_SESSION['pqz_configuration']['default_response_type'];
    if ($response_type == "options") {
// Multiple Options 
        foreach ($single_quiz_data['possible_answer'] as $key => $possible_answer) {
            $checked = ($answer == $possible_answer) ? " checked " : "";
            echo "\t";
            echo '<input type="radio" name="q_' . $question_index . '" value="' . $key . '" ' . $checked . '>';
            echo $possible_answer;
            echo "<br>\n";
        }
    } else {
        $possible_answer = isset($single_quiz_data['answered_question']) ? $single_quiz_data['answered_question'] : "";
        $opt = "";
        /*
         *     $opt = "tabindex=$tab_index ";
          if ($tab_index == 1)
          $opt .= "autofocus"; */

        echo "<input type=\"text\" name=\"q_$question_index\" value=\"$possible_answer\" $opt >";
    }

    echo "</td></tr>\n";
}

function emit_question_status($question_index) {
// mostra le domande a cui si è risposto e quelle che mancano
    global $base_img_path;


    $option = isset($_REQUEST['option']) ? filter_input(INPUT_GET, 'option') : "";
    $content = "<table>\n";

    foreach ($_SESSION['pqz_question'] as $id => $single_question) {
        if ($id == $question_index) {
            $extra_stle = "outline: thin solid black;";
        } else {
            $extra_stle = "";
        }
        $link = "?id_question=$id&task=ask_question&option=" . $option;
        $content .= "<tr style=\"$extra_stle\" class=\"question_status\">\n";
        $content .= "\t<td><a href=\"$link\">" . ($id + 1) . "</a></td>";
        $content .= "<td><a href=\"$link\">";
        if (isset($single_question['answered_question'])) {
            $img_name = "$base_img_path/Circle-question-green.png";
        } else {
            $img_name = "$base_img_path/Circle-question-yellow.png";
        }
        $content .= "<img src=\"$img_name\" />";
        $content .= "</a></td><tr>\n";
    }

    $content .= "</table>\n";
    return $content;
}

function check_all_answer() {
    $all_answer = true;
    foreach ($_SESSION['pqz_question'] as $question) {
        if (!isset($question['answered_question'])) {
            $all_answer = false;
        }
    }

    return $all_answer;
}

function store_answers() {
// TODO CHECK input

    foreach ($_REQUEST as $key => $val) {
        if (substr($key, 0, 2) == "q_") {
            $id = (int) substr($key, 2);
            $_SESSION['pqz_question'][$id]["answered_question"] = $val;
        }
    }
}

function check_max_min_starting_question($starting_question) {
    return min(max(0, $starting_question), $_SESSION['pqz_configuration']['max_question_total'] - 1);
}

function emit_results() {
    quiz_check_answer();

    $data_quiz = $_SESSION['pqz_question'];

    $num_question = count($data_quiz);
    echo '<div style="width:100%; background:#F2F2F2">';
    echo '<table style="border: 1px solid black; width=100%; margin: 0 ; " >';
    echo '<tr ><td>';
    echo '<table border="1">';
    for ($question_index = 0; $question_index < $num_question; $question_index++) {
        if ($data_quiz[$question_index]['response_type'] == "options") {
            $answer = $data_quiz[$question_index]['possible_answer'][$data_quiz[$question_index]["answered_question"]];
        } else {
            $answer = $data_quiz[$question_index]["answered_question"];
        }
        $correct_answer = $data_quiz[$question_index]["all_correct_answer"][0];
        echo "<tr>\n";
        echo '<td>' . $data_quiz[$question_index]["question"] . "</td>\n";

        if ($data_quiz[$question_index]['answer_is_correct']) {
            // the answer is correct
            $style_color = 'green';
            $text = $answer;
            $explanation_text = $data_quiz[$question_index]['if_correct'];
        } else {
            // the answer wrong
            $style_color = 'red';
            $text = "Hai risposto : <b>$answer</b><br />Ma la risposta giusta era: <b>$correct_answer</b><br />";
            $explanation_text = $data_quiz[$question_index]['if_wrong'];
        }
        echo "<td style=\"background-color:$style_color;\">$text</td>\n";
        echo "<td>$explanation_text</td>\n";
        echo "</tr>\n";
    }
    echo "</table>\n";
    echo "</td><td style=\"vertical-align:top;\">";
    echo 'hai risposto giusto ' . $_SESSION['pqz_configuration']['num_correct_answer'] . ' su ' . $num_question . ' domande';
    if ($_SESSION['pqz_configuration']['num_correct_answer'] == $num_question) {

        $img = $_SESSION['pqz_configuration']['congratulation_img'];
        echo "<br>\n";
        echo "<img src=\"$img\" style=\"max-width:800px;\" ><br>\n";
    }

    echo "</td></tr>\n";
    echo "</table>\n";
    echo '<div>';

    echo '<a href="' . $_SERVER["PHP_SELF"] . '?task=start_quiz&quiz_conf' . '=' . $_SESSION['pqz_configuration']['quiz_ini_conf'] . '">RICOMINCIA</a> ';
}

function quiz_check_answer() {
    $num_correct_answer = 0;
    foreach ($_SESSION['pqz_question'] as $key => $data_single_quiz) {
        $all_right_question = array();
        foreach ($data_single_quiz['all_correct_answer'] as $single_correct_answer) {
            $all_right_question[] = trim(strtoupper($single_correct_answer));
        }
        $answered_question = trim(strtoupper($data_single_quiz["answered_question"]));
        if (in_array($answered_question, $all_right_question)) {
            $_SESSION['pqz_question'][$key]['answer_is_correct'] = TRUE;
            $num_correct_answer++;
        } else {
            $_SESSION['pqz_question'][$key]['answer_is_correct'] = FALSE;
        }
    }
    $_SESSION['pqz_configuration']['num_correct_answer'] = $num_correct_answer;
}
