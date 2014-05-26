<?php

/*
 * Class to edit CSV
 *  v 20140402.01
 */

class csv_gd {

    public $csv_filename;
    public $delimiter = ";";

    function __construct($csv_filename = "") {
        $this->csv_filename = $csv_filename;
    }

    public function set_filename($csv_filename) {
        $this->csv_filename = $csv_filename;
    }

    public function csv_to_array() {
        // convert a csv file to an array
        $delimiter = $this->delimiter;
        $filename = $this->csv_filename;
        if (!file_exists($filename)) {
            echo "CSV_GD: $filename NOT FOUND";
            return FALSE;
        } elseif (!is_readable($filename)) {
            echo "CSV_GD: $filename NOT READABLE";
            return FALSE;
        }
        $header = NULL;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                if (empty($row)) {
                    continue;
                }
                if (!$header) {
                    $header = $row;
                } else {
                    if (!empty($row[0])) {
                        $data[] = array_combine($header, $row);
                      
                    }
                }
            }
            fclose($handle);
        }
        return $data;
    }

    public function pick_one_field_random($column = "") {

        $csv_array = $this->csv_to_array();
        $id = array_rand($csv_array);
        if (empty($column)) {
            return $csv_array[$id];
        } else {
            return $csv_array[$id][$column];
        }
    }

    public function array_to_csv($array) {
        // convert an csv to an array
		
        $filename = $this->csv_filename;
        $fp = fopen($filename, "wb");
		ksort($array[0]);
        fputcsv($fp, array_keys($array[0]), $this->delimiter);
        foreach ($array as $fields) {
			ksort($fields);
            fputcsv($fp, $fields, $this->delimiter);
        }
        fclose($fp);
    }

  
}

