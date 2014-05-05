<?php

class PqzModelPqz_model extends JModelItem {

    protected $items;

    public function getMsg() {
        // REMOVE THIS
        $results = "aa";
        return $results;
    }

    public function getItems() {
      //  $this->items = "jjj";
        return "SSS";
    }

    public function display($cachable = false, $urlparams = false) {
        echo "model display";
        echo "model display";

        return 0;
    }

    public function choose_quiz($quiz_dir) {
  return "UNUSED";
    }

    public function start_quiz($cachable = false, $urlparams = false) {
        echo "model start_quiz";
        return 0;
    }

    public function ask_question($cachable = false, $urlparams = false) {
        echo "model ask_question";
        return 0;
    }

    public function show_results($cachable = false, $urlparams = false) {
        echo "model show_results";
        return 0;
    }

}
