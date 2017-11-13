<?php
/**
 * mysql.class.php
 * Copyright (C) 2006-2007  leelight
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @version $Id$
 * @copyright (C) 2006-2007  leelight
 * @Description: This class performes several operations in MySQL .
 * @contact webmaster@easywms.com
 */
class Database {
    /**
     * PRIVATE PROPERTIES
     */
    public $databaseConnection; // Conection to the database.
    public $databaseHost; // IP of the MySQL host.
    // public $databasePort;			// MySQL Port.
    public $databaseUser; // MySQL Login.
    public $databasePass; // MySQL Password.
    public $databaseDb; // Database name.
    public $databaseError; // Error name.
    public $databaseErrNo; // Error number.
    public $databaseErrorMessage = ""; // Error message.
    public $databaseLocked; // Lock flag.
    public $databaseFeedback; // Text of answer.
    public $databaseFile; // Name of the file for the backup and restore. (Ex. /tmp/backup/backup.sql)
    public $log; //for using getLog4Databae
    public $recordgood = 0;  //record number that inserted successfully
    public $recordbad = 0;   //record number that hasnt inserted


    function get_database_db()
    {
        return $this->database_db;
    }

    function set_database_db($value)
    {
        $this->mysql_db = $value;
    }

    function get_database_file()
    {
        return $this->database_file;
    }

    function set_database_file($value)
    {
        $this->database_file = $value;
    }

    function getDatabaseError()
    {
        return $this->databaseError;
    }

    function setDatabaseError($value)
    {
        $this->databaseError = $value;
    }

    /**
     *
     * @Description :	Handle errors.																												 	**
     * @params :error: Error occurs.
	 * @notice: use special PHP function																																					**
     */
    function databaseErrorHandle($error)
    {
        $this->databaseError = @mysql_error($this->databaseConnection);
        $this->databaseErrNo = @mysql_errno($this->databaseConnection);
        // if ($this->mysql_locked)
        // $this->mysql_unlock();
        return $this->databaseErrorFormat($error);
    }

    /**
     *
     * @Description :	Format the error and output																																**
     * @params :error: Error occurs.
     */
/*
  function databaseErrorFormat($error) {
    $errormessage = "<h1>Failure</h1>"."<p id=\"intro\">You must correct the error below before process can continue:<br><br>";
    $errormessage .= "<span style=\"color:#000000\"><b>Error: ".$error."</b></span><br><br>";
    $errormessage .= "<b>MySQL Error</b>: ".$this->databaseError." (".$this->databaseErrNo.")<br>";
    $errormessage .= "<a href=\"javascript: history.go(-1)\">Click here to go back</a>.</p>";
    return $errormessage;
  }
*/

    /**
     *
     * @Description :	Format the error and output	for xml																																	**
     * @params :error: Error occurs.
     */
    function databaseErrorFormat($error)
    {
        $this->databaseErrorMessage = "Error: " . $error . "<br> \n";
        $this->databaseErrorMessage .= "MySQL Error: " . $this->databaseError . " (" . $this->databaseErrNo . ")<br>\n";
        //http://dev.mysql.com/doc/refman/5.0/en/error-messages-server.html
        //$this->databaseErrorMessage .="Refer to <a href=\"http://dev.mysql.com/doc/refman/5.0/en/error-messages-server.html\" target=\"_blank\">MySQL Error Messages</a>";
        return $this->databaseErrorMessage;
    }
    function databaseGetErrorMessage()
    {
        return trim($this->databaseErrorMessage);
    }

    /**
     *
     * @DESCRIPTION :Class Constructor.
     */
    function database()
    {
    }

    /**
     *
     * @Description :Set the host, port, login, password	and database.																													 																																					**
     * @params :host: Host IP where MySQL is.
     * @params :user: MySQL Login.
     * @params :pass: MySQL Password.
     * @params :db: Database name.
     */
    function databaseConfig($host, $user, $pass, $db)
    {
        $this->databaseHost = $host;
        $this->databasePass = $pass;
        $this->databaseUser = $user;
        $this->databaseDb = $db;
        $this->log = "";
        $this->recordgood = 0;
        $this->recordbad = 0;
    }

    /**
     *
     * @Description :Create the connection to the database.																													 	**
     * @notice use special php function
     */
    function databaseConnect()
    {
        $this->databaseConnection = @mysql_connect($this->databaseHost, $this->databaseUser, $this->databasePass);
        if (!$this->databaseConnection){
            $this->databaseErrorHandle("Can not connect to Database! Please check your connection, username and password.");
			}
        $success = @mysql_select_db($this->databaseDb);
        //@mysql_query("SET NAMES 'utf8'");
        if (!$success)
            $this->databaseErrorHandle("Database could not be opened!");
    }

    /**
    * @Description :Create the connection without database.
    * @notice use special php function
    */
    function databaseConnectNoDatabase()
    {
        $this->databaseConnection = @mysql_connect($this->databaseHost, $this->databaseUser, $this->databasePass);
        if (!$this->databaseConnection){
            $this->databaseErrorHandle("Can not connect to Database! Please check your connection, username and password.");}
    }

    /**
     *
     * @Description :Get the database version as array
     * @return :	version string
	 * @notice use special php function																																		**
     */
    function getDatabaseVersion()
    {
        $version = mysql_query('SELECT VERSION() AS version', $this->databaseConnection);
        $result = mysql_fetch_array($version);
        if (!$result)
            $this->databaseErrorHandle("Can not get database version!");

        return $result;
    }

    /**
     *
     * @Description :Get the database names list object
     * @return :	database name list object
	 * @notice use special php function																																			**
     */
    function getDatabaseName()
    {
        $databasename = @mysql_list_dbs($this->databaseConnection);
        if (!$databasename)
            $this->databaseErrorHandle("Can not get database name!");

        return $databasename;
    }

    /**
     *
     * @Description :delete one table with name
	 * @params : tablename																																**
     */
    function databaseDeleteTable($tablename)
    {
        $result = @mysql_query("DROP TABLE $tablename", $this->databaseConnection);
        if (!$result){
            $this->databaseErrorHandle("Can not delete this table $tablename!");
            return false;
        }
        return true;
    }

    /**
     *
     * @Description :Get the table names list object
     * @params : databasename
     * @return :	table name list
	 * @notice use special php function																																**
     */
    function getTableName($databasename)
    {
        $tablename = @mysql_list_tables($databasename);
        if (!$tablename)
            $this->databaseErrorHandle("Can not get tables from $databasename!");

        return $tablename;
    }

    /**
     *
     * @Description :Get the columns object from table
     * @params : tablename
     * @return :	columns list																																			**
     */
    function getColumnsFromTable($tablename)
    {
        $columns = @mysql_query("SHOW COLUMNS FROM $tablename", $this->databaseConnection);
        if (!$columns)
            $this->databaseErrorHandle("Can not get columns from $tablename!");

        return $columns;
    }

    /**
     *
     * @Description :Excute any SQL query																																	**
     */
    function databaseAnyQuery($sqlstring)
    {
        $result = @mysql_query($sqlstring, $this->databaseConnection);
        if (!$result) {
            $this->databaseErrorHandle("Can not excute this SQL: ");
            return false;
        } else
            return true;
    }

    /**
     *
     * @Description :Create a database																																	**
     */
    function databaseCreateDatabase($databasename)
    {
        $result = @mysql_query("CREATE DATABASE $databasename", $this->databaseConnection);
        if (!$result){
            $this->databaseErrorHandle("Can not create this database $databasename!");
            return false;
        }
        return true;
    }

    /**
     *
     * @Description :delete a database																																	**
     */
    function databaseDeleteDatabase($databasename)
    {
        $result = @mysql_query("DROP DATABASE $databasename", $this->databaseConnection);
        if (!$result){
            $this->databaseErrorHandle("Can not delete this database $databasename!");
             return false;
        }
        return true;
    }

    /**
     *
     * @Description :Test the privilege to create and drop database																																**
     */
    function databaseCheckCreateAndDropDatabasePrivelege()
    {
        $testtablename = "test".date("Ymd");
        //later could rewrite code here, to avoid create error log
        $result = $this->databaseCreateDatabase($testtablename);
        if($result){
		    $result = $this->databaseDeleteDatabase($testtablename);
		}

        return $result;
    }

    /**
     *
     * @Description :Close the connection of the database.
     * @notice use special php function
     */
    function databaseClose()
    {
        $result = @mysql_close($this->databaseConnection);
        if (!isset($result))
            $this->databaseErrorHandle("Can not close the connection of database!");
    }

    /**
     *
     * @Description :Get the rows object group by one column
     * @params : tablename: table name
     * @params : column: column name for group
     * @return : columns
     */
    function getRowsGroupBy($tablename, $column)
    {
        @$rows = mysql_query("SELECT * FROM " . $tablename . " GROUP BY " . $column, $this->databaseConnection);
        if (!isset($rows))
            $this->databaseErrorHandle("Query error in getRowsGroupBy");

        return $rows;
    }

    /**
     *
     * @Description :Get the rows by given layer
     * @params : tablename: table name																											 	**
     * @params : layer: where = '' in sql
     * @return : rows																																				**
     */
    function getRowsByLayer($tablename, $currentlayer)
    {
        @$rows = mysql_query("SELECT * FROM " . $tablename . " WHERE layer = '" . $currentlayer . "'", $this->databaseConnection);
        if (!isset($rows)){
            $this->databaseErrorHandle("Query error in getRowsByLayer");
            return null;
        }
        return $rows;
    }

    /**
     * @TODO should be renamed as getFetchArray()
     * @Description :Fetch a result row as an associative array, a numeric array, or both
     * By using MYSQL_BOTH (default), you'll get an array with both associative and number indices.
	 * Using MYSQL_ASSOC, you only get associative indices (as mysql_fetch_assoc() works),
	 * using MYSQL_NUM, you only get number indices (as mysql_fetch_row() works).
	 *
     * @params : record: record array
     * @return : columns in array
     * @notice use special php function
     */
    function getColumns($result)
    {
        $columns = @mysql_fetch_array($result, MYSQL_BOTH);
        if (!$columns){
        //it is strange, the last element in while($row=$database->getColumns($result)) always is wrong!
            //$this->databaseErrorHandle("mysql_fetch_array error in getColumns");
            //return false;
        }

        return $columns;
    }

    /**
     *
     * @Description :Get a result row as an enumerated array
     * @params : record: columns
     * @return : rows in array
     * @notice use special php function
     */
    function getRows($result)
    {
        @$rows = mysql_fetch_row($result);
        if (!$rows){
            $this->databaseErrorHandle("mysql_fetch_row error in getRows");
            return false;
        }

        return $rows;
    }

    /**
     *
     * @Description :Get number of fields in result
     * @params : rows: rows array
     * @return : fields number as integer
     * @notice use special php function
     */
    function getFieldsNumber($result)
    {
	    @$num = mysql_num_fields($result);
        if (!$num)
            $this->databaseErrorHandle("mysql_num_fields error in getFieldsNumber");

        return $num;
    }

    /**
     *
     * @Description :Get the columns number
     * @params : rows: rows array
     * @return : columns number
     * @notice use special php function
     */
    function getColumnsNumber($field)
    {
	    @$num = mysql_num_rows($field);
        if (!isset($num))
            $this->databaseErrorHandle("mysql_num_rows error in getColumnsNumber");

        return $num;
    }


     /**
     *
     * @Description : Create table for suas mendatory table (featuregeometry, featureclass) if not exist, leave featuregeometry and delete others
     * clear the feature class, but leave the featuregeometry
     * @params : $tables_sql: sql from tables.php in Models
     * @params : $tablenamePrefix: table name prefix
     * @return true if successful
     * @usage: install/3.php
     */
	function createTableForSUASIfNotExist($tables_sql, $tablenamePrefix){
        //foreach ($tables_sql as $sql) {
        for($i=0; $i< count($tables_sql); $i++){
        	if($i!=0){
	            // Replace with the user defined tablename
	            $sql = str_replace(array(mapTableFeaturegeometry, mapTableFeatureclass),
						array($tablenamePrefix.mapTableFeaturegeometry, $tablenamePrefix.mapTableFeatureclass), $tables_sql[$i]);
	            $result = $this->databaseAnyQuery($sql);
	            if(!$result){
	            	$this->databaseErrorHandle("Query error in createTableForSUASIfNotExist");
					return false;
				}
			}
        }
        return true;
	}

	/**
     *
     * @Description : Create table for suas mendatory table (featuregeometry, featureclass), if alreay exist, return back with false.
     * @params : $tables_sql: sql from tables.php in Models
     * @params : $tablenamePrefix: table name prefix
     * @return true if successful
     * @usage: install/3.php
     */
	function createTableForSUAS($tables_sql, $tablenamePrefix){
        //foreach ($tables_sql as $sql) {
        for($i=0; $i< count($tables_sql); $i++){
        	if($i!=1){
	            // Replace with the user defined tablename
	            $sql = str_replace(array(mapTableFeaturegeometry, mapTableFeatureclass),
						array($tablenamePrefix.mapTableFeaturegeometry, $tablenamePrefix.mapTableFeatureclass), $tables_sql[$i]);

	            $result = $this->databaseAnyQuery($sql);
	            if(!$result){
					$this->databaseErrorHandle("Table prefix ".$tablenamePrefix." already exists.Please give new one.");
					return false;
				}
			}
        }
        return true;
	}


	/**
     *
     * @Description : delete table for suas mendatory table (featuregeometry, featureclass)
     * @params : $tablenamePrefix: table name prefix
     * @return true if successful
     * @usage: setting/s3b.php
     */
	function deleteTableForSUAS($tablenamePrefix){
		if(!$this->databaseDeleteTable($tablenamePrefix.mapTableFeaturegeometry)){
			return false;
		}
		if(!$this->databaseDeleteTable($tablenamePrefix.mapTableFeatureclass)){
			return false;
		}

		return true;
	}

	/**
     *
     * @Description : empty table for suas table (featuregeometry, featureclass)
     * @params : $tablenamePrefix: table name prefix
     * @return true if successful
     * @usage: setting/s2aempty.php
     */
	function emptyTableForSUAS($tablenamePrefix){
		if(!$this->makeTableEmpty($tablenamePrefix.mapTableFeaturegeometry))
			return false;
		if(!$this->makeTableEmpty($tablenamePrefix.mapTableFeatureclass))
			return false;

		return true;
	}

	//function emptyClassTableForSUAS(){

	//}

	/**
	* @Description : get the status of all the tables
	* @return recordset when successful, false if failed
	* @output structure: 18 rows
	* Name  Engine  Version  Row_format  Rows  Avg_row_length  Data_length  Max_data_length
	* Index_length  Data_free  Auto_increment  Create_time  Update_time  Check_time
	* Collation  Checksum  Create_options  Comment
	*/
	function showTableStatus(){
		$result = @mysql_query("SHOW TABLE STATUS;", $this->databaseConnection);
		if (!$result){
            $this->databaseErrorHandle("Query error in ShowTableStatus");
            return false;
        }

        return $result;
	}

	/**
     *
     * @Description : get the datail inforamtion of table
     * @params : $table: table name
     * @return array for Rows, Data_length,Create_time, Update_time, or array with "unknown" if failed
     * @usage:
     */
	function getTableDetailInformation($tablename){
		$result = $this->showTableStatus();
		$row = $this->getColumns($result);
		$array = array();

		$array[0] = "unknown";
		$array[1] = "unknown";
		$array[2] = "unknown";
		$array[3] = "unknown";
		// the while here is not loop with the count of row(here is 18x2=36), but the tables' number in the database
		//why 18x2? because the id and field name are both stored, ig. 0, Nmae, 1 , Engine, 2, Version............
		while ($row = $this->getColumns($result)) {
			$tem = $row["Name"];
			if($tem == $tablename){
				$array[0] = $row["Rows"];
				$array[1] = round($row["Data_length"]/1024);
				$array[2] = $row["Create_time"];
				$array[3] = $row["Update_time"];
				return $array;
			}

		}
		return $array;
	}

	/**
     *
     * @Description : check if the table has the standard fields inside or not
     * @params : $table: table name
     * @return true if successful
     * @usage: setting/s2a.php, s2aempty.php   install/2b.php
     */
	function TableHasSameFields($tablename){
	    try {
	    	$arrayfields = explode("|", mapTableFieldsFeaturegeometry);
	    	$length = count($arrayfields);
	    	$number = 0;
	    	$columns = $this->getColumnsFromTable($tablename);

            //$row is every column in the table, and $rows has 12 description for it
	        while ($row = $this->getColumns($columns)) {
	            $tem = $row["Field"];
	            //echo $row["Type"];
	            for($i=0;$i<$length;$i++){
					if($tem != $arrayfields[$i])
						$number++;
				}
				if($number==$length)
					return false;
				$number = 0;
#	            if ($tem != "id" && $tem != "layer" && $tem != "recid" && $tem != "geomtype" && $tem != "xmin" && $tem != "ymin" && $tem != "xmax" && $tem != "ymax" && $tem != "geom" && $tem != "svgxlink" && $tem != "srs" && $tem != "attributes" && $tem != "style") {
#	                return false;
#	            }
	        }

	    }
	    catch(Exception $e) {
	    	$this->databaseErrorHandle("Error in TableHasSameFields.");
	        return false;
	    }
	    return true;
}

//======================================For Metadata==========================================================================
    /**
     *
     * @Description :Get the rows group by one column
     * @params : tablename: table name, for featureclass table
     * @params : column: column name for group
     * @return : columns
     */
    function getRows4MetaGroupBy($tablename, $column)
    {
        @$rows = mysql_query("SELECT * FROM " . $tablename . " WHERE visiable = '1' GROUP BY " . $column, $this->databaseConnection);
        if (!$rows)
            $this->databaseErrorHandle("Query error in getRows4MetaGroupBy");

        return $rows;
    }


    /**
     *
     * @Description :Get the rows by srs
     * @params : tablename: table name(for featureclass table)
     * @params : $currentsrs: where = '' in sql
	 * @params : $column: one column, for example layer, then select the rows that has no repeat layername
     * @return : rows																																				**
     */
    function getRowsBySrsGroupBy($tablename, $currentsrs, $column)
    {
        @$rows = mysql_query("SELECT * FROM " . $tablename . " WHERE srs = '" . $currentsrs . "' AND visiable = '1' GROUP BY " . $column, $this->databaseConnection);
        if (!$rows)
            $this->databaseErrorHandle("Query error in getRowsBySrsGroupBy");

        return $rows;
    }

    /**
     *
     * @Description :Get the rows by srs
     * @params : tablename: table name(for featureclass  and featuregeometry table)
     * @params : $currentsrs: where = '' in sql
     * @params : $column: one column, for example layer, then select the rows that has no repeat layername
     * @return : rows																			     *
     */
    function getLayersBySrsGroupByLayer($tablename, $currentsrs)
    {
        @$rows = mysql_query("SELECT DISTINCT layer FROM " . $tablename . " WHERE srs = '" . $currentsrs . "' GROUP BY layer", $this->databaseConnection);
        if (!$rows)
            $this->databaseErrorHandle("Query error in getLayersBySrsGroupByLayer");

        return $rows;
    }

    /**
     *
     * @Description :Get the rows of minx miny maxx maxy for all layers and srss
     * @params : tablename: table name(for featureclass table)
     * @return : rows
     */
    function getRowsMinMaxXY($tablename)
    {
        @$rows = mysql_query("SELECT MIN(xmin), MIN(ymin), MAX(xmax), MAX(ymax) FROM $tablename WHERE visiable = '1'", $this->databaseConnection);
        if (!$rows)
            $this->databaseErrorHandle("Query error in getRowsMinMaxXY");

        return $rows;
    }

    /**
     *
     * @Description :Get the rows of minx miny maxx maxy which srs is given
     * @params : tablename: table name(for featureclass table)
     * @params : currentsrs: current srs name
     * @return : rows
     */
    function getRowsMinMaxXYBySrs($tablename, $currentsrs)
    {
        @$rows = mysql_query("SELECT MIN(xmin), MIN(ymin), MAX(xmax), MAX(ymax) FROM $tablename WHERE visiable = '1' AND srs = '$currentsrs'", $this->databaseConnection);
        if (!$rows)
            $this->databaseErrorHandle("Query error in getRowsMinMaxXYBySrs");

        return $rows;
    }

    /**
     *
     * @Description :Get the rows of minx miny maxx maxy which layer is given, Only used for GetFeatureInfo
     * @params : tablename: table name(for featureclass table)
     * @params : currentsrs: current layer name
     * @return : rows
     */
    function getRowsMinMaxXYByLayer($tablename, $currentlayer)
    {
        @$rows = mysql_query("SELECT MIN(xmin), MIN(ymin), MAX(xmax), MAX(ymax) FROM $tablename WHERE visiable = '1' AND layer = '$currentlayer'", $this->databaseConnection);
        if (!!$rows)
            $this->databaseErrorHandle("Query error in getRowsMinMaxXYByLayer");

        return $rows;
    }

    /**
     *
     * @Description :Get the rows of minx miny maxx maxy which srs and layer is given
     * @params : tablename: table name
     * @params : currentsrs: current srs name
     * @params : currentlayername: current layer name
     * @return : rows
     */
    function getRowsMinMaxXYBySrsLayer($tablename, $currentsrs, $currentlayername)
    {
        @$rows = mysql_query("SELECT MIN(xmin), MIN(ymin), MAX(xmax), MAX(ymax) FROM $tablename WHERE srs = '$currentsrs' AND layer ='$currentlayername'", $this->databaseConnection);
        if (!$rows){
        //echo "SELECT MIN(xmin), MIN(ymin), MAX(xmax), MAX(ymax) FROM $tablename where srs = '$currentsrs' AND layer ='$currentlayername'";
            $this->databaseErrorHandle("Query error in getRowsMinMaxXYBySrsLayer");
        }
        return $rows;
    }

    /**
     *
     * @Description :Get the rows when layer and srs is given
     * @params : tablename: table name (for featureclass table)
     * @params : currentsrs: current srs name
     * @params : currentlayername: current layer name
     * @return : rows
     */
    function getRowsBySrsLayer($tablename, $currentsrs, $currentlayername)
    {
        @$rows = mysql_query("SELECT * FROM $tablename where visiable = '1' AND srs = '$currentsrs' AND layer ='$currentlayername'", $this->databaseConnection);
        if (!$rows)
            $this->databaseErrorHandle("Query error in getRowsBySrsLayer");

        return $rows;
    }

    /**
     *
     * @Description :Get the layers' priority array
     * @params : tablename: table name (for featureclass table)
     * @params : currentsrs: current srs name
     * @params : layerarray: layers array
     * @return : priority array
     */
    function getPriorityArray($tablename, $currentsrs, $layerarray)
    {
        $numberofvalueslayer = count($layerarray);
        for ($i = 0; $i < $numberofvalueslayer; $i++) {
            @$rows = mysql_query("SELECT priority FROM $tablename where visiable = '1' AND srs = '$currentsrs' AND layer ='$layerarray[$i]'", $this->databaseConnection);
            if (!$rows)
                $this->databaseErrorHandle("Query error in getPriorityArray");

            $line= $this->getColumns($rows);
            $arrayPriority[$i] = $line[0];
        }
        return $arrayPriority;
    }

    /**
     *
     * @Description :Get the rows where layer is currentlayer and group by layer and srs
     * @params : tablename: table name(for featureclass table)
     * @params : $currentlayername: current layername
     * @return : rows																																				**
     */
    function getRowsByLayerGroupBy($tablename, $currentlayername, $columns)
    {
        @$rows = mysql_query("SELECT * FROM  $tablename  WHERE visiable = '1' AND layer = '$currentlayername' GROUP BY $columns", $this->databaseConnection);
        if (!$rows)
            $this->databaseErrorHandle("Query error in getRowsByLayerGroupBy");

        return $rows;
    }



//======================================For Metadata==========================================================================

    /**
    * For what?
    *
    */
    function getRowsInBboxBySrsLayer($tablename, $minx, $miny, $maxx, $maxy, $currentsrs, $currentlayername)
    {
        @$rows = mysql_query("SELECT * FROM  $tablename  WHERE xmin >= $minx AND ymin >= $miny AND xmax <= $maxx AND ymax <= $maxy AND srs = '$currentsrs' AND layer ='$currentlayername'", $this->databaseConnection);
        if (!$rows)
            $this->databaseErrorHandle("Query error in getRowsInBboxBySrsLayer");

        return $rows;
    }

    /**
    * special for Getfeature create GML
    */
    function getGeomAsTextBySrsLayer($tablename, $currentsrs, $currentlayername)
    {
        @$rows = mysql_query("SELECT id,layer,recid,geomtype,xmin,ymin,xmax,ymax,AsText(geom),svgxlink,srs,attributes,style FROM  $tablename  WHERE  srs = '$currentsrs' AND layer ='$currentlayername'", $this->databaseConnection);
        if (!$rows)
            $this->databaseErrorHandle("Query error in getGeomAsTextBySrsLayer");

        return $rows;
    }

    /**
    * Using BBOX spatial analysis to select bbox, faster but can not display the geometies completly
    *
    * Using WKT spatial analysis to select bbox, can display the geometies completly but slowly
    */
    function getGeomAsTextInBboxBySrsLayer($tablename, $minx, $miny, $maxx, $maxy, $currentsrs, $currentlayername)
    {
        if(blnGetMap25D) $ratio = GetMap25DOverlapRatio;
        else $ratio = OverlapRatio;
        //@$rows = mysql_query("SELECT id,layer,recid,geomtype,xmin,ymin,xmax,ymax,AsText(geom),svgxlink,srs,attributes,style FROM  $tablename  WHERE xmin >= $minx AND ymin >= $miny AND xmax <= $maxx AND ymax <= $maxy AND srs = '$currentsrs' AND layer ='$currentlayername'", $this->databaseConnection);
        $distanceX = $maxx - $minx;
        $distanceY = $maxy - $miny;
        $overlapX = $distanceX * $ratio;
        $overlapY = $distanceY * $ratio;
        $x0 = $minx-$overlapX;
        $y0 = $miny-$overlapY;
        $x1 = $minx-$overlapX;
        $y1 = $maxy+$overlapY;
        $x2 = $maxx+$overlapX;
        $y2 = $maxy+$overlapY;
        $x3 = $maxx+$overlapX;
		$y3 = $miny-$overlapY;

		@mysql_query("SET @bbox = GeomFromText('Polygon(($x0 $y0,$x1 $y1,$x2 $y2,$x3 $y3,$x0 $y0))')", $this->databaseConnection);
        //@mysql_query("SET @bbox = GeomFromText('Polygon(($minx $miny,$minx $maxy,$maxx $maxy,$maxx $miny,$minx $miny))')", $this->databaseConnection);
		@$rows = mysql_query("SELECT id,layer,recid,geomtype,xmin,ymin,xmax,ymax,AsText(geom),svgxlink,srs,attributes,style FROM  $tablename WHERE MBRIntersects(geom,@bbox) = '1' AND srs = '$currentsrs' AND layer ='$currentlayername'", $this->databaseConnection);
		if (!$rows)
            $this->databaseErrorHandle("Query error in getGeomAsTextInBboxBySrsLayer");

        return $rows;
    }




    /**
     *
     * @Description :Get the rows in BBox and in the select square where layer is currentlayer in GetFeatureInfo
     * @params : tablename: table name
     * @params : minx,miny,maxx,maxy: BBox
     * @params : x_plus,x_minus,y_plus,y_minus: select point square
     * @params : currentlayername: current layername
     * @return : rows																																				**
     */
    function getSelectFeatureInBoxBy($tablename, $minx, $miny, $maxx, $maxy, $x_plus, $x_minus, $y_plus, $y_minus, $currentlayername)
    {
        @$rows = mysql_query("SELECT * FROM  $tablename WHERE xmin > $minx AND ymin > $miny AND xmax < $maxx AND ymax < $maxy AND xmin >= '$x_minus' AND xmax <= '$x_plus' AND ymin >= '$y_minus' AND ymax <= '$y_plus' AND layer = '" . $currentlayername . "'", $this->databaseConnection);
        if (!$rows)
            $this->databaseErrorHandle("Query error in getSelectFeatureInBoxBy");

        return $rows;
    }

    /**
     *
     * @Description :Get the rows in the select square where layer is currentlayer in GetFeatureInfo
     * @params : tablename: table name
     * @params : x_plus,x_minus,y_plus,y_minus: select point square
     * @params : currentlayername: current layername
     * @return : rows																																				**
     */
    function getSelectFeatureInSquareBy($tablename, $x_plus, $x_minus, $y_plus, $y_minus, $currentlayername)
    {
        //@$rows = mysql_query("SELECT * FROM $tablename WHERE xmin >= '$x_minus' AND xmax <= '$x_plus' AND ymin >= '$y_minus' AND ymax <= '$y_plus' AND layer = '" . $currentlayername . "'", $this->databaseConnection);
        @mysql_query("SET @square = GeomFromText('Polygon(($x_minus $y_minus,$x_minus $y_plus,$x_plus $y_plus,$x_plus $y_minus,$x_minus $y_minus))')", $this->databaseConnection);
        @$rows = mysql_query("SELECT * FROM $tablename WHERE MBRIntersects(geom,@square) = '1' AND layer = '$currentlayername'", $this->databaseConnection);

        if (!$rows)
            $this->databaseErrorHandle("Query error in getSelectFeatureInSquareBy");
        return $rows;
    }

    /**
     *
     * @Description :Get the rows by queryable layer
     * @params : tablename: featureclass table																											 	**
     * @params : layer: where = '' in sql
     * @return : rows																																				**
     */
    function getRowsByQueryableLayer($tablename, $currentlayer)
    {
        @$rows = mysql_query("SELECT * FROM " . $tablename . " WHERE queryable = '1' AND layer = '" . $currentlayer . "'", $this->databaseConnection);
        if (!$rows)
            $this->databaseErrorHandle("Query error in getRowsByQueryableLayer");

        return $rows;
    }

    /**
     *
     * @Description :Insert the records into database featuregeometry
     * @params : tablename: table name
     * @params : $values: field value list
     * @return : ture or false																																				**
     */
    function databaseInsertGeometry($tablename, $layer, $recid, $geomtype, $xmin, $ymin, $xmax, $ymax, $geom, $svgxlink, $srs, $attributes, $style)
    {
        $fields = 'id,layer,recid,geomtype,xmin,ymin,xmax,ymax,geom,svgxlink,srs,attributes,style';
        $values = "'NULL','" . $layer . "','" . $recid . "','" . $geomtype . "','" . $xmin . "','" . $ymin . "','" . $xmax . "','" . $ymax . "',GeomFromText('" . $geom . "'),'" . $svgxlink . "','" . $srs . "','" . $attributes . "','" . $style . "'";
        $sql = "INSERT INTO " . $tablename . " (" . $fields . ") VALUES (" . $values . ")";
        //echo $sql."\n";
        $result = @mysql_query($sql, $this->databaseConnection);
        if ($result) {
            $this->recordgood ++;
		    }
        if (!$result) {
            //echo "Error:<br>";
            //echo $sql."<br>";
            $this->recordbad++;
            $this->log .= "Error  $this->recordbad :\n".$sql."\n";
            $this->databaseErrorHandle("Error when insert records into database!");
        }
    }

    function getIdFromRS($rs){
	    return $rs["id"];
	}
	function getRecidFromRS($rs){
	    return $rs["recid"];
	}
    function getGeometryTextFromRS($rs){
	    return $rs[8];
	}
	function getStyleFromRS($rs){
	    return $rs["style"];
	}
	function getGeomtypeFromRS($rs){
	    return strtoupper($rs["geomtype"]);
	}
	function getSvgxlinkFromRS($rs){
	    return $rs["svgxlink"];
	}
	function getAttributesFromRS($rs){
	    return $rs["attributes"];
	}
	function getSrsFromRS($rs){
	    return $rs["srs"];
	}

//==========================================For CSV input data===========================================================
     /**
     *
     * @Description :Insert the records into database featuregeometry with LOAD DATA INFILE method
     * @params : tablename: table name
     * @params : $values: field value list
     * @return : ture or false																																				**
     */
/*
    function inputCSV2DatabaseOld($filename,$tablename,$csvTerminated,$csvEnclosed,$csvEscaped,$bCsvheader,$arrCsvColumns){
	    $sql = "LOAD DATA INFILE '".@mysql_escape_string($filename).
             "' INTO TABLE `".$tablename.
             "` FIELDS TERMINATED BY '".@mysql_escape_string($csvTerminated).
             "' OPTIONALLY ENCLOSED BY '".@mysql_escape_string($csvEnclosed).
             "' ESCAPED BY '".@mysql_escape_string($csvEscaped).
             "' ".
             ($bCsvheader ? " IGNORE 1 LINES " : "")
             ."(`".implode("`,`", $arrCsvColumns)."`)";

         $result = @mysql_query($sql, $this->databaseConnection);
         if (!$result) {
            $this->databaseErrorHandle("Error when input CSV file into database!");
        }

	}
*/
     /**
     *
     * @Description :Insert the records into database featuregeometry
     * @params : tablename: table name
     * @params : $values: field value list
     * @return : ture or false																																				**
     */
    function inputCSV2Database($filename,$tablename,$csvTerminated,$csvEnclosed,$csvEscaped,$bCsvheader,$arrCsvColumns){
        //delete the ' in array
        $arrCsvColumns = str_replace('\'', ' ', $arrCsvColumns);
        $handle = fopen ($filename,"r");
        if ($handle)
        {
        //ignore the first line
        if ($bCsvheader){
		    $data = fgetcsv ($handle, 10*1024, $csvTerminated);
		}
        $sql="INSERT INTO ". $tablename ."(".implode(",", $arrCsvColumns).")". "VALUES(";

        while ($data = fgetcsv ($handle, 10*1024, $csvTerminated)){
            $num = count ($data);//echo $num;
            for ($c=0; $c < $num; $c++) {
                //Replace single quote ' with 2 singe quotes '' , the single quote will not change in the database
                $data[$c] = str_replace('\'', '\'\'', $data[$c]);
                //if id is empty, give it value NULL to be compatible MySQL 5.1 strict
                if($data[0]==""){
                     $data[0] = "NULL";
                }
                if($c==$num-1){
				    $sql .= "'".$data[$c]."')";
					break;
				}
				else{
				    //Geometry column 8
				    if($c ==8){
				        $data[$c] = "GeomFromText('".$data[$c]."')";
				        $sql .= $data[$c].",";
				    }
				    else
                        $sql .= "'".$data[$c]."',";
                }
            }
            //echo $sql."\n";
            $result = @mysql_query($sql, $this->databaseConnection);
            if ($result) {
                $this->recordgood ++;
		    }
            if (!$result) {
                $this->recordbad++;
                $this->log .= "Error  $this->recordbad :\n".$sql."\n";
                $this->databaseErrorHandle("Error when input CSV file into database!");
            }

            $sql="INSERT INTO ". $tablename ."(".implode(",", $arrCsvColumns).")". "VALUES(";
        }
        }
        fclose ($handle);
	}


//==========================================For CSV import data===========================================================
    /**
    * @Description :This function will escape the unescaped_string , so that it is safe to place it in a mysql_query()
    *
    */
    function getMysql_escape_string($filename){
	    $filename = @mysql_escape_string($filename);
        return $filename;
	}

    /**
    * used in MIF2DB
    *
    */
	function updateTextAngle($tablename, $xmin, $ymin, $xmax, $ymax, $geom,$recid, $attributes){
		$sql = "UPDATE `$tablename` SET `attributes` = '$attributes',`xmin` = '$xmin',`ymin` = '$ymin',`xmax` = '$xmax', " .
		"`ymax` = '$ymax',`geom` = GeomFromText('" . $geom . "') WHERE `recid` = '$recid' LIMIT 1";
		$result = @mysql_query($sql, $this->databaseConnection);

		if (!$result) {
            $this->recordbad++;
            $this->log .= "Error  $this->recordbad :\n".$sql."\n";
            $this->databaseErrorHandle("Error when insert Angle attribute for Text!");
        }
	}

    /**
    * @Description :record log when meet error during the data inputting
    *
    */
    function getLog4Database(){
	    $log = $this->log;
        return $log;
	}

    /**
     *
     * @Description :Get the geometry as WKT test
     * @params : geometry object
     * @return : text																																				**
     */
    function getGeometryAsText($geometry)
    {
        $text = @mysql_query("SELECT AsText($geometry)", $this->databaseConnection);

        if (!$text)
            $this->databaseErrorHandle("Error when read Geometry!");

        return $text;
    }

//================================For Installation create style==============================================================
    /**
     *
     * @Description :Get all the layer name from table featuregeometry, without repeat name!
     * @params: tablename
     * @return :	layer names list																																			**
     */
    function getAllLayersNames($tablename)
    {
        $layersnames = @mysql_query("SELECT layer,geomtype FROM $tablename GROUP by layer",$this->databaseConnection);
        if (!$layersnames)
            $this->databaseErrorHandle("Can not get layers' names!");

        return $layersnames;
    }

        /**
     *
     * @Description :Get all the layer name from table featuregeometry, without repeat name!
     * @params: tablename
     * @return :	layer names list																																			**
     */
    function getAllLayersNamesInSrs($tablename,$srsname)
    {
        $layersnames = @mysql_query("SELECT layer,geomtype FROM $tablename where srs = '$srsname' GROUP by layer",$this->databaseConnection);
        if (!$layersnames)
            $this->databaseErrorHandle("Can not get layers' names!");

        return $layersnames;
    }

        /**
     *
     * @Description :Get all the srs by srs
     * @params : tablename: table name(for featuregeometry table)
     * @return : rows																																				**
     */
    function getAllSrssNames($tablename)
    {
        @$rows = mysql_query("SELECT DISTINCT srs FROM " . $tablename . "", $this->databaseConnection);
        if (!$rows)
            $this->databaseErrorHandle("Query error in getAllSrssNames");

        return $rows;
    }

    /**
     *
     * @Description :Get all the layer name from table featuregeometry, layer to srs:1 to n, srs to layer:1 to n
     * @params: tablename
     * @return :	layernames and srs list																																			**
     */
    function getLayersNamesWithDiffSrs($tablename)
    {
        $layersnamesrs = @mysql_query("SELECT DISTINCT layer,srs FROM $tablename",$this->databaseConnection);
        if (!$layersnamesrs)
            $this->databaseErrorHandle("Can not get layers' names and srs");

        return $layersnamesrs;
    }


    /**
     *
     * @Description :make the old table empty
     * @params: tablename
     * @return :ture if successful																																		**
     */
    function makeTableEmpty($tablename)
    {
        $result = @mysql_query("TRUNCATE TABLE $tablename",$this->databaseConnection);
        if (!$result){
            $this->databaseErrorHandle("Can not empty the table ".$tablename);
            return false;
        }
        return true;
    }

     /**
     *
     * @Description :Insert the records into database featureclass
     * @params : tablename: table name
     * @params : $values: field value list
     * @return : ture or false																																				**
     */
    function createFeatureClassMetadata($tablename, $layertype, $layer, $description, $geomtype, $xmin, $ymin, $xmax, $ymax, $srs, $style, $queryable, $visiable, $priority,$elevation)
    {
        $fields = 'layertype,layer,description,geomtype,xmin,ymin,xmax,ymax,srs,style,queryable,visiable,priority,elevation';
        $values = "'" . $layertype . "','" . $layer . "','" . $description . "','" . $geomtype . "','" . $xmin . "','" . $ymin . "','" . $xmax . "','" . $ymax . "','" . $srs . "','" . $style . "','" . $queryable . "','" . $visiable . "','" . $priority . "','".$elevation."'";
        $sql = "INSERT INTO " . $tablename . " (" . $fields . ") VALUES (" . $values . ")";
        //echo $sql."\n";
        $result = @mysql_query($sql, $this->databaseConnection);
        if (!$result) {
        //echo "error!!!!!!!!!!";
        //echo $sql."\n";
            $this->databaseErrorHandle("Error when creating the metadata in database!");
        }
    }

    function deleteLayersInSrs($tablename, $srs){
        $result = @mysql_query("DELETE FROM $tablename WHERE srs ='$srs'",$this->databaseConnection);
        if (!$result){
            $this->databaseErrorHandle("Can not delete layers in srs ".$srs);
            return false;
        }
        return true;
	}

     /**
     *
     * @Description :Insert the records into database featureclass
     * @params : tablename: table name
     * @params : $srs: srs name
     * @params : $layers: layers names string, layer1,layer2,layer3,........
     * @return : ture or false																																				**
     */
	function deleteLayersBySRS($tablename, $srs, $layers){
        $layers =  str_replace(",", "','", $layers);
        $layers = "'".$layers."'";
        $result = @mysql_query("DELETE FROM $tablename WHERE srs ='$srs' AND layer IN ($layers)",$this->databaseConnection);
        if (!$result){
            $this->databaseErrorHandle("Can not delete layers ".$layers);
            return false;
        }
        return true;

	}

//================================For Installation==============================================================




    /**
     * NAME:																																		**
     *
     * 	mysql_update ($table,$sets,$condition)																	**
     *
     *
     * 	DESCRIPTION:																													 	**
     *
     * 	Execute the sentence UPDATE.																						**
     *
     *
     * 	ARGUMENTS:																															**
     *
     * 	-	table: Name of the table.																							**
     * 	- sets: List of the SET clauses to update.															**
     * 	-	condition: Rules to update the regs.																	**
     */
    function mysql_update($table, $sets, $condition)
    {
        $noerror = true;
        $sqld = "UPDATE " . $table . " SET " . $sets . " WHERE " . $condition;
        if (!($noerror = $this->mysql_query($sqld)))
            $this->mysql_feedback .= $this->mysql_manejo_errores("Imposible ");
        else
            $this->mysql_feedback .= "  ";

        return $noerror;
    }


    /**
     * NAME:																																		**
     *
     * 	mysql_backup ()																													**
     *
     *
     * 	DESCRIPTION:																													 	**
     *
     * 	Do the backup and put it as a file defined by mysql_file.								**
     *
     *
     * 	ARGUMENTS:																															**
     */
    function mysql_backup()
    {
        $linea = 'mysqldump -u ' . $this->mysql_user . ' -p' . $this->mysql_pass . ' ' . $this->mysql_db . ' > ' . $this->mysql_file;
        exec($linea, $algo, $error);

        if ($error == 0)
            $this->mysql_feedback .= " Backup";
        else
            $this->mysql_feedback .= $this->mysql_manejo_errores("Error when Backup!!");

        return $error;
    }

    /**
     * NAME:																																		**
     *
     * 	mysql_restore ()																												**
     *
     *
     * 	DESCRIPTION:																													 	**
     *
     * 	Restore the backup defined by mysql_file.																**
     *
     *
     * 	ARGUMENTS:																															**
     */
    function mysql_restore()
    {
        $linea = 'mysql --debug=d:t:o,/tmp/mysql.trace ' . $this->mysql_db . ' < ' . $this->mysql_file;
        exec($linea, $algo, $error);

        if ($error == 0)
            $this->mysql_feedback .= " Restore";
        else
            $this->mysql_feedback .= $this->mysql_manejo_errores("ErrorRestore!!");

        return $error;
    }

    /**
     * NAME:																																		**
     *
     * 	mysql_lock ($table, $mode = "write")																		**
     *
     *
     * 	DESCRIPTION:																													 	**
     *
     * 	Block the access to the table(s) for the mode.													**
     *
     *
     * 	ARGUMENTS:																															**
     *
     * 	-	tabla: Name(s) of the table(s) to protect.														**
     * 	-	mode: Mode of protection. Those are:																	**
     * 					-	read																													**
     * 					-	read local																										**
     * 					-	write							 																						**
     * 					-	low priority write																						**
     */
    function mysql_lock($tabla, $mode = "write")
    {
        /*$noerror = true;

    $sql = "lock tables ";
    if(is_array($tabla)){
      while(list($key,$valor) = each($tabla)){
        if(is_int($key))
        	$key = $mode;
        if(strpos($valor, ","))
          $sql .= str_replace(",", " $key, ", $value) . " $key, ";
        else
          $sql .= "$valor $key, ";
      }
      $sql = substr($sql, 0, -2);
    }else if(strpos($tabla, ","))
      $sql .= str_replace(",", " $mode, ", $tabla) . " $mode";
    else
      $sql .= "$tabla $mode";

    if(!$this->mysql_query($sql)){
      $this->mysql_feedback .= $this->mysql_manejo_errores("Bloqueo de tablas fall&#243!!");
    	$noerror = false;
    }

    $this->locked = true;

    return $noerror;*/
    }

    /**
     * NAME:																																		**
     *
     * 	mysql_unlock ()																													**
     *
     *
     * 	DESCRIPTION:																													 	**
     *
     * 	Unblock the tables.																											**
     *
     *
     * 	ARGUMENTS:																															**
     */
    function mysql_unlock()
    {
        /* $noerror = true;
    $this->locked = false;

		if(!($noerror = $this->mysql_query("unlock tables")))
      $this->mysql_feedback .= $this->mysql_manejo_errores("Desbloqueo de tablas fall&#243!!");

    return $noerror;*/
    }
}

?>