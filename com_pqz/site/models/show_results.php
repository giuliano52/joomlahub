<?php

class PqzModelshow_results extends JModelItem {

    public function quiz_check_answer() {
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

}
