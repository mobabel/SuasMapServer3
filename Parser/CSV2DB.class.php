<?php
/*
     - use CSV header or not: if yes, first line of the file will be recognized as CSV header, and all database columns will be called so, and this header line won't be imported in table content.
	    If not, the table columns will be calles as "layer,recid,geomtype,xmin,ymin,xmax,ymax,svggeom,svgxlink,srs,attributes,style"
     - separate char: character to separate fields, comma [,] is default
     - enclose char: character to enclose those values that contain separate char in text, quote ["] is default
     - escape char: character to escape special symbols like enclose char, back slash [\] is default
*/

//require_once '../Models/MySQL.class.php';

class CSV2DB {
    private $database;
    private $servername, $username, $password, $dbname, $tbname;

    private $file_name; //where to import from
    private $use_csv_header = true; //use first line of file as column names
    private $text_csv_terminated = ","; //character to separate fields
    private $text_csv_enclosed = "\""; //character to enclose fields, which contain separator char into content
    private $text_csv_escaped = "\\"; //char to escape special symbols
    public $error; //error message
    public $recordgood, $recordbad;
    private $arr_csv_columns = array(); //array of columns
    private $table_exists = true; //flag: does table for import exist

    function CSV2DB($servername, $username, $password, $dbname, $tbname, $file_name, $use_csv_header,
        $text_csv_terminated, $text_csv_enclosed, $text_csv_escaped, $table_exists)
    {
        $this->file_name = $file_name;
        $this->use_csv_header = $use_csv_header;
        $this->text_csv_terminated = $text_csv_terminated;
        $this->text_csv_enclosed = $text_csv_enclosed;
        $this->text_csv_escaped = $text_csv_escaped;
        $this->table_exists = $table_exists;

        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->tbname = $tbname;
        $this->arr_csv_columns = array();

        $database = new Database();
        $database->databaseConfig($this->servername, $this->username, $this->password, $this->dbname);
        $database->databaseConnect();

        $this->database = $database;

        $this->error = $this->database->databaseGetErrorMessage();
    }

    function import()
    {
        if ($this->table_name == "") {
            $this->error = "Can not find table, please check your database.";
        }
        // if(empty($this->arr_csv_columns))
        $this->get_csv_header_fields();

        if ($this->table_exists) {
            if ($this->database->databaseGetErrorMessage() == "") {
                $this->database->inputCSV2Database($this->file_name, $this->tbname, $this->text_csv_terminated, $this->text_csv_enclosed,
                    $this->text_csv_escaped, $this->use_csv_header, $this->arr_csv_columns);
            }
            $this->error = $this->database->databaseGetErrorMessage();
            $this->recordgood = $this->database->recordgood;
            $this->recordbad = $this->database->recordbad;
            $this->log = $this->database->log;
        }
    }
    // returns array of CSV file columns
    function get_csv_header_fields()
    {
        $this->arr_csv_columns = array();
        $this->arr_csv_columns_noh = array();
        if (file_exists($this->file_name)) {
            $handle = fopen($this->file_name, "r");

            if ($handle) {
                $arr = fgetcsv($handle, 10 * 1024, $this->text_csv_terminated);
                if (is_array($arr) && !empty($arr)) {
                    if ($this->use_csv_header) {
                        foreach($arr as $val) {
                            if (trim($val) != "") {
                                $this->arr_csv_columns[] = $val;
                            }
                            // echo $val."|\n";
                        }
                    } else {
                        $this->arr_csv_columns_noh = array(id, layer, recid, geomtype, xmin, ymin, xmax, ymax, geom, svgxlink, srs, attributes, style);
                        $i = 0;
                        foreach($arr as $val) {
                            $this->arr_csv_columns[] = $this->arr_csv_columns_noh[$i];
                            // echo $this->arr_csv_columns_noh[$i]."-\n";
                            $i++;
                        }
                    }
                    // print_r($this->arr_csv_columns);
                }
                unset($arr);
                fclose($handle);
            } else {
                return $this->error = "File cannot be opened: " . ("" == $this->file_name ? "[empty]" : $this->database->getMysql_escape_string($this->file_name));
            }
        }
        else{
            return $this->error = "The CSV file $this->file_name does not exist";
		}
        // print_r($this->arr_csv_columns);
        return $this->arr_csv_columns;
    }
}
// connect database
// mysql_connect("localhost", "root", "test");
// mysql_select_db("qq");

// $csv2db = new CSV2DB();
// $csv2db->file_name = "C:\Apache\Apache2\htdocs\phpmywms\CSV_test.txt";
// $csv2db->table_name = "wms_featuregeometry";

// $csv2db->use_csv_header = false;
// $csv2db->text_csv_terminated = ",";
// $csv2db->text_csv_enclosed = "";
// $csv2db->text_csv_escaped = "";

// $csv2db->table_exists=true;
// //start import now
// $csv2db->import();
?>
