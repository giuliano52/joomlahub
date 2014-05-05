<?php

class PqzModelask_question extends JModelItem {

    
    public function store_answers() {
        // TODO CHECK input

        foreach ($_REQUEST as $key => $val) {
            if (substr($key, 0, 2) == "q_") {
                $id = (int) substr($key, 2);
                $_SESSION['pqz_question'][$id]["answered_question"] = $val;
            }
        }
    }
    
}
