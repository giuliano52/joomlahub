<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$single_quiz_data = $_SESSION['pqz_question'][$this->question_index];
$answer = isset($single_quiz_data['answered_question']) ? $single_quiz_data['answered_question'] : "";
$response_type = isset($single_quiz_data['response_type']) ? $single_quiz_data['response_type'] : $_SESSION['pqz_configuration']['default_response_type'];
$question = str_replace("\n", "<br>\n", $single_quiz_data['question']);
?>


<?= $this->loadTemplate("question_status"); ?>

<div style="border: 1px solid black;">
    <form action="<?= JRoute::_("index.php"); ?>" method="get" autocomplete="off">

        <table style="table-layout:fixed; width:100%;	">
            <tr>
                <th style="width: 60%"><?= JText::_('COM_PQZ_ANSWER_THIS_QUESTION') ?></th>
                <th>&nbsp;</th>
            </tr>
            <tr>
                <td style="vertical-align: top;">
                    <div style="font-size:large;border: 1px solid black;border-radius: 15px; padding: 10px;line-height:150%;">
                        <?= $question; ?>
                    </div>
                </td>
                <td >

                    <?php
                    if ($response_type == "options") {
                        // Multiple Options 
                        foreach ($single_quiz_data['possible_answer'] as $key => $possible_answer) {
                            $checked = ($answer == strval($key)) ? " checked " : "";
                            $question_name = "q_" . $this->question_index;
                            $possible_answer_br = str_replace("\n", "<br>\n", $possible_answer);
                            ?>
                            <div style="border: 1px solid black; margin-bottom: 2px; margin-right: 20px; border-radius: 15px; padding: 10px;background-color: #F0F0F0">
                                <label>
                                    <input type="radio" style="vertical-align: middle" name="<?= $question_name; ?>" value="<?= $key; ?>" <?= $checked; ?> >
                                    <?= $possible_answer_br; ?>

                                </label>
                            </div>
                            <?php
                        }
                    } else {
                        $possible_answer = isset($single_quiz_data['answered_question']) ? $single_quiz_data['answered_question'] : "";

                        echo "<input type=\"text\" name=\"q_{$this->question_index}\" value=\"$possible_answer\"  >";
                    }
                    ?>

                </td>
            </tr>


            <tr>
                <?php
                if ($this->all_answered) {
                    ?>
                    <td colspan="2">
                        <?= JText::_('COM_PQZ_ALL_ANSWER') ?>
                        <a href="<?= JRoute::_("index.php?task=show_results") ?>"> 
                            <b> <?= JText::_('COM_PQZ_SEE_RESULTS') ?></b>
                        </a>
                    </td>
                    <?php
                } else {
                    ?>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        <input type="submit" value="Prossimo" Name="Nav">
                    </td>
                    <?php
                }
                ?>
            </tr>
        </table>

        <input type="hidden" name="option" value="com_pqz">
        <input type="hidden" name="task" value="ask_question">
        <input type="hidden" name="store_answer" value="true">
        <input type="hidden" name="id_question" value="<?= $this->question_index + 1 ?>">

    </form>


</div>

