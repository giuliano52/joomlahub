<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$data_quiz = $_SESSION['pqz_question'];
$num_question = count($data_quiz);
?>

<div >  <!-- main-->
    <div style=" position:relative; top:0; left:0; width:100%;"> <!-- header -->
        <?php
        $num_correct_answer = $_SESSION['pqz_configuration']['num_correct_answer'];
        echo JText::sprintf('COM_PQZ_YOU_ANSWERED_ON_QUESTION', $num_correct_answer, $num_question);
        ?>
    </div>

    <!-- content -->
    <table style="table-layout:fixed; width:100%;border: 1px solid black">
        <tr>
            <th style="width:30%;"><?= JText::_('COM_PQZ_QUESTION'); ?></th>
            <th style="width:30%;"><?= JText::_('COM_PQZ_ANSWER'); ?></th>
            <th style="width:40%;"><?= JText::_('COM_PQZ_COMMENT'); ?></th>
        </tr>
        <?php
        for ($question_index = 0; $question_index < $num_question; $question_index++) {
            if ($data_quiz[$question_index]['response_type'] == "options") {
                $answer = $data_quiz[$question_index]['possible_answer'][$data_quiz[$question_index]["answered_question"]];
            } else {
                $answer = $data_quiz[$question_index]["answered_question"];
            }
            $correct_answer = $data_quiz[$question_index]["all_correct_answer"][0];

            if ($data_quiz[$question_index]['answer_is_correct']) {
                // the answer is correct

                $style_color = 'green';
                $text = "<div style=\"margin:5px\">$answer</div>";
                $explanation_text = $data_quiz[$question_index]['if_correct'];
            } else {
                // the answer wrong
                $style_color = 'red';
                $style_error = "border: 1px solid black;margin:5px; ";
                $text = JText::_('COM_PQZ_YOUR_ANSWER_WAS') .
                        "<div style=\"$style_error background-color:LightPink;\">$answer</div>" .
                        JText::_('COM_PQZ_BUT_CORRECT_ONE_WAS') .
                        "<div style=\"$style_error background-color:LightGreen; \">$correct_answer</div> ";
                $explanation_text = $data_quiz[$question_index]['if_wrong'];
            }
            ?>
            <tr style="border:1px solid black; margin:3px;">
                <td>
                    <div style="margin: 5px;">
                        <?= $data_quiz[$question_index]["question"]; ?>
                    </div>
                </td>
                <td style="background-color:<?= $style_color ?>;">
                    <?= $text; ?>
                </td>
                <td>
                    <div style="margin: 5px;">
                        <?= $explanation_text; ?>
                    </div>
                </td>
            </tr>


            <?php
        }
        ?>

    </table>

    <div style=" position:relative; top:0; right:0; width:100%;">
        <?php
        if ($_SESSION['pqz_configuration']['num_correct_answer'] == $num_question) {

            $img = $_SESSION['pqz_configuration']['congratulation_img'];
            echo "<br><img src=\"$img\" width=\"800\"><br>\n";
        }
        ?>
    </div>

</div>

<a href="<?= JRoute::_("index.php?task=start_quiz&quiz_conf={$_SESSION['pqz_configuration']['quiz_ini_conf']}") ?>"> 
    <?= JText::_('COM_PQZ_RESTART_QUIZ'); ?>

</a>


