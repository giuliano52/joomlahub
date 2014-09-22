<?php

/*
 * Version 20140327.02
 */

class pqz {

    public $configuration;
    public $question;
    public $debug = false;

    function __construct() {
        $this->configuration['base_data_dir'] = 'data';
        /* base data for the configuartion files 
         * must have the following folder
         *      csv: all the csv file
         *      ini: all the information for the ini quiz
         *      conf: all the configuration needed
         */
        $this->configuration['base_ini_dir'] = 'data/ini';    // the base configuration for all ini files
    }

    public function scanIniDir($dir) {
        /*
         * scan ini dir and return an array with the information for that particular dir
         * TODO: sort by type and by name
         */
        
        //prevent ../../../ attach
        $full_data_dir = realpath($this->configuration['base_ini_dir']);
        $full_dir_unsafe = realpath($full_data_dir . '/' . $dir);
        $full_dir = $full_data_dir . str_replace($full_data_dir, '', $full_dir_unsafe);

        $a_out = array();
        if (!is_dir($full_dir_unsafe)) {
            die("pqz.class.php: Directory $dir Not Found / full dir $full_dir");
        }

        $scanned_directory = array_diff(scandir($full_dir), array('..', '.'));
        $index = 0;
        foreach ($scanned_directory as $entry) {

            if (is_dir("$full_dir/$entry")) {
                $a_out[$index]['type'] = "dir";
                $a_out[$index]['path'] = $dir . $entry . '/';
                $a_out[$index]['name'] = $entry;
                $index ++;
            } else {
                // is a file

                $filename = $full_dir . '/' . $entry;

                $file_parts = pathinfo($filename);
                if (strtolower($file_parts['extension']) == 'ini') {
                    
                } else {
                    if ($this->debug) {
                        echo "$filename NOT an ini file";
                    }
                }
                $ini_array = parse_ini_file($filename);

                if (isset($ini_array['title'])) {
                    $a_out[$index] = $ini_array;
                    $a_out[$index]['type'] = "file";
                    $a_out[$index]['path'] = $dir . $entry;
                    $a_out[$index]['name'] = $entry;
                    $index ++;
                }
            }
        }


        // --- sort the multidimensional array ---
        // Obtain a list of columns
        foreach ($a_out as $key => $row) {
            $type[$key] = $row['type'];
            $name[$key] = $row['name'];
            $path[$key] = $row['path'];
        }

// Sort the data with volume descending, edition ascending
// Add $data as the last parameter, to sort by the common key
        array_multisort($type, SORT_ASC, $name, SORT_ASC, $path, SORT_ASC, $a_out);

        return $a_out;
    }

    public function start_quiz($quiz_ini_conf) {

        $this->read_ini_conf('default.ini', $this->configuration['base_data_dir'] . '/ini/');
        $this->read_ini_conf($quiz_ini_conf);
        $this->generate_question();
        $this->setSESSIONvariables();
        if ($this->debug) {
            print_pre($_SESSION['pqz_configuration']);
            print_pre($_SESSION['pqz_question']);
        }
    }

    private function generate_question() {
        /* generate the array $quiz_questions with all the question 
         */

        require_once(__DIR__ . '/csv_gd.class.php');
        $csv_filename = $this->configuration['base_data_dir'] . '/csv/' . $this->configuration['csv_input'];

        $data_quiz_src_obj = new csv_gd($csv_filename);
        $data_quiz_src_orig = $data_quiz_src_obj->csv_to_array();


        // filter unwanted question (tags, diff level, void )
        $data_quiz_src_filtered = $this->quiz_filter($data_quiz_src_orig, $this->configuration['tags'], $this->configuration['min_diffucult_level'], $this->configuration['max_diffucult_level']);


        // reverse question with_answer
        if ($this->configuration['reverse_question'] == TRUE) {
            $data_quiz_src_filtered = $this->quiz_switch_question_with_answer($data_quiz_src_filtered);
        }


        // generate question and answer
        $data_quiz_src = $this->quiz_generate($data_quiz_src_filtered);


        // randomize quiz 
        if ($this->configuration['randomize_question'] == TRUE) {
            shuffle($data_quiz_src);
        }


        // restrict the maxium number of question
        if ($this->configuration['max_question_total'] > 0) {
            $this->configuration['max_question_total'] = min(count($data_quiz_src), $this->configuration['max_question_total']);
        } else {
            $this->configuration['max_question_total'] = count($data_quiz_src);
        }
        $this->question = array_slice($data_quiz_src, 0, $this->configuration['max_question_total']);



        // return $this->quiz_questions;
    }

    private function quiz_switch_question_with_answer($data_quiz_src) {
        $out = array();
        $index = 0;
        foreach ($data_quiz_src as $single_quiz) {
            $out[$index] = $single_quiz;
            $out[$index]['question'] = $single_quiz['correct_answer'];
            $out[$index]['correct_answer'] = $single_quiz['question'];
            $index ++;
        }
        return $out;
    }

    private function quiz_filter($data_quiz_src, $tags, $min_difficult_level, $max_difficult_level) {
        $out = array();
        $a_tags = explode('|', $tags);
        foreach ($data_quiz_src as $single_quiz) {
            $difficult_level = !empty($single_quiz['difficult_level']) ? $single_quiz['difficult_level'] : 1;
            if (!empty($single_quiz['question']) && !empty($single_quiz['correct_answer'])) {
                if (($difficult_level >= $min_difficult_level) && ($difficult_level <= $max_difficult_level)) {
       
                    if (empty($tags)) {
                        $out[] = $single_quiz;
                    } else {
                        // tags are selected
                        $tag_found = false;
                        foreach ($a_tags as $single_tag) {

                            if (stripos($single_quiz['tags'], $single_tag) !== false) {
                                $tag_found = true;
                            }
                        }
                        if ($tag_found) {

                            $out[] = $single_quiz;
                        }
                    }
                }
            }
        }
        return $out;
    }

    private function read_ini_conf($quiz_ini_conf, $full_dir = '') {
        /*
         * Read quiz configuration and merge to other $this->configuration values
         */
        $base_ini_dir_original = $this->configuration['base_ini_dir'];
        $full_data_dir = realpath($this->configuration['base_ini_dir']);

        if ($full_dir == '') {
            $full_file_unsafe = realpath($full_data_dir . '/' . $quiz_ini_conf);
            $full_file = $full_data_dir . str_replace($full_data_dir, '', $full_file_unsafe);
        } else {
            $full_file = $full_dir . $quiz_ini_conf;
        }

        if (file_exists($full_file)) {
            $ini_array = parse_ini_file($full_file);
            $this->configuration = array_merge($this->configuration, $ini_array);
            // prevent change to $this->configuration['base_ini_dir'] from ini.file restoring original one
            $this->configuration['base_ini_dir'] = $base_ini_dir_original;
            $this->configuration['quiz_ini_conf'] = $quiz_ini_conf;
        }
        else {
            die("read_ini_conf: $full_file DONT EXIST");
        }
          

        require_once(__DIR__ . '/csv_gd.class.php');
        $csv_filename = $this->configuration['base_data_dir'] . '/conf/' . $this->configuration['congratulation_file'];
        $csv_file = new csv_gd($csv_filename);
        $this->configuration['congratulation_img'] = $csv_file->pick_one_field_random('url');
    }

    private function setSESSIONvariables() {
        // store to _SESSION the variables 
        $_SESSION['pqz_configuration'] = $this->configuration;
        $_SESSION['pqz_question'] = $this->question;
    }

    private function quiz_generate($data_quiz_src) {
        // generate question correct response and so on ... 
        // extract all possible answer
        $all_possible_answer = array();
        foreach ($data_quiz_src as $single_quiz) {
            $all_correct_answer = explode('|', $single_quiz['correct_answer']);
            $all_possible_answer[] = $all_correct_answer[0];
        }

        $out = array();
        $index = 0;
        foreach ($data_quiz_src as $single_quiz) {
            shuffle($all_possible_answer);

            $single_quiz['all_correct_answer'] = explode('|', $single_quiz['correct_answer']);
            $single_quiz['possible_answer'] = array($single_quiz['all_correct_answer'][0]);

            $single_quiz['response_type'] = !empty($single_quiz['response_type']) ? $single_quiz['response_type'] : $this->configuration['default_response_type'];

            //  add wrong answer to $possible_answer from input data
            if (isset($single_quiz["wrong_answer"])) {
                $possible_wrong_answer = explode('|', $single_quiz["wrong_answer"]);
                $single_quiz['possible_answer'] = array_merge($single_quiz['possible_answer'], $possible_wrong_answer);
            }

            //  add random wrong answer to $possible_answer
            $single_quiz['possible_answer'] = array_merge($single_quiz['possible_answer'], $all_possible_answer);

            // eliminate duplicates
            $single_quiz['possible_answer'] = array_unique($single_quiz['possible_answer']);

            //remove eventually emtpy element
            $single_quiz['possible_answer'] = array_filter($single_quiz['possible_answer']);

            //get only the correct number of elements:
            $single_quiz['response_type'] = strtolower($single_quiz['response_type']);
            $option_array = explode('_', $single_quiz['response_type']);
            if (isset($option_array[1])) {
                $max_option = $option_array[1];
                $single_quiz['response_type'] = $option_array[0];
            } else {
                $max_option = $this->configuration['num_options'];
            }

            $single_quiz['possible_answer'] = array_slice($single_quiz['possible_answer'], 0, $max_option);

            // shuffle all data:
            shuffle($single_quiz['possible_answer']);

            $single_quiz['all_question'] = explode('|', $single_quiz['question']);
            shuffle($single_quiz['all_question']);

            $single_quiz['question'] = $single_quiz['all_question'][0];


            // add id of the correct_answer to correct answer if the answer_type is 'options' 
            if ($single_quiz['response_type'] == "options") {
                $key = array_search($single_quiz['all_correct_answer'][0], $single_quiz['possible_answer']);
                $single_quiz['all_correct_answer'][] = $key;
            }


            $output_keys = array(
                'id',
                'question',
                'all_correct_answer',
                'possible_answer',
                'response_type',
                'if_correct',
                'if_wrong'
            );


            $out[$index] = array();
            foreach ($output_keys as $single_key) {

                $out[$index][$single_key] = isset($single_quiz[$single_key]) ? $single_quiz[$single_key] : "";
            }

            $index ++;
        }
        return $out;
    }

}
