<?php

 /**
 * MIF2DB Class
 * @original from author:<ovidio AT users.sourceforge.net>
 *
 * @description: Import the points coordinates and use according class to output the wkt string
 *               Used in read the SVG, SHP, E00 or MOF format
 *
 * @Task :Calculation for Points XY's Min&Max value
 * @Task :Output WKT format
 * @version $3.0$ 2007
 * @Author leelight
 * @Contact webmaster@easywms.com
 */
//require_once '../Models/MySQL.class.php';
require_once '../Models/Setting.class.php';
require_once 'Geometry2WKT.class.php';
require_once 'AttributeParser.class.php';

class MIF2DB {
    private $database;
    private $servername, $username, $password, $dbname, $tbname, $layerName, $SRSname;
    private $fileNameMif;
    public $error="", $log="";
    public $recordgood = 0, $recordbad= 0, $recordNumber= 0;
    private $SHPFile;
    private $textRecid, $textx1, $textx2, $texty1, $texty2;
    private $textattribute;

    public function MIF2DB($servername_, $username_, $password_, $dbname_, $tbname_, $layerName_, $SRSname_ ,$fileNameMif_)
    {
        $this->servername = $servername_;
        $this->username = $username_;
        $this->password = $password_;
        $this->dbname = $dbname_;
        $this->tbname = $tbname_;
        $this->layerName = $layerName_;
        $this->SRSname = $SRSname_;
        $this->fileNameMif = $fileNameMif_;


        $database = new Database();
        $database->databaseConfig($this->servername, $this->username, $this->password, $this->dbname);
        $database->databaseConnect();
        $this->database = $database;

        $this->error = $this->database->databaseGetErrorMessage();
        if($this->error!="")
            $this->setError($this->error);
        else
		    $this->loadFromFile();
    }

    public function loadFromFile()
    {
        $this->openMIFFile();
        $this->LoadMIF();

        $this->recordgood = $this->database->recordgood;
        $this->recordbad = $this->database->recordbad;
        $this->log = $this->database->getLog4Database();
        $this->closeMIFFile();
    }

    function openMIFFile($toWrite = false)
    {   if(file_exists($this->fileNameMif)){
            $this->MIFFile = @fopen($this->fileNameMif, ($toWrite ? "wb+" : "rb"));
            if (!$this->MIFFile) {
            return $this->setError(sprintf("It is not possible to open the MIF file '%s'", $this->fileNameMif));
            }
        }
        else{
		    return $this->setError(sprintf("The MIF file '%s' does not exist", $this->fileNameMif));
		}


        return true;
    }

    function closeMIFFile()
    {
        if ($this->MIFFile) {
            fclose($this->MIFFile);
            $this->MIFFile = null;
        }
    }

    function formatLine($string){
	    return strtoupper($string);
    }

  function LoadMIF()
{
	$arrGeometry = array();
	$in_data = false;

	while (!feof($this->MIFFile))
	{
 		$line = trim(fgets($this->MIFFile, 1024));

		if(substr($this->formatLine($line),0,4)=="DATA")
		{
			$in_data=true;
		}
		else if($in_data)
		{
            //POINT x y
            //[ SYMBOL (shape, color, size)]
		    if(substr($this->formatLine($line),0,5)=="POINT"){
				$array = explode(" ",$line);
				$arrGeomInfo = array();
				$arrGeomInfo["xmin"] = $x   = trim($array[1]);
				$arrGeomInfo["ymin"]  = $y     = trim($array[2]);
				$arrGeomInfo["xmax"] = $x = trim($array[1]);
				$arrGeomInfo["ymax"]  = $y  = trim($array[2]);
				//$arrGeomInfo["objectsType"] = "POINT";
				//$arrGeomInfo["pointCount"]  = 1;
				//$arrGeomInfo["pointString"] = "$x $y";

				//$tmp = explode(" ", $arrGeomInfo["pointString"]);
                $pointparser = new PointParser(false);
                $resultarray = $pointparser->parser($x, $y);
                $this->database->databaseInsertGeometry($this->tbname, $this->layerName, $this->layerName . "_" . $this->recordNumber, GeometryTypePoint,
                    $resultarray[0], $resultarray[1], $resultarray[2], $resultarray[3], $resultarray[4], "", $this->SRSname, $this->attributes, StyleDefault);
                if ($this->database->databaseGetErrorMessage() != "") {
                    $this->error = $this->database->databaseGetErrorMessage();
                }

                $this->recordNumber++;
			}
			//LINE x1 y1 x2 y2
		    //line -88.937195 30.418255 -88.937248 30.418261

			else if(substr($this->formatLine($line),0,4)=="LINE")
			{
				$array = explode(" ",$line);
				$arrGeomInfo = array();
				$arrGeomInfo["xmin"] = $x1   = trim($array[1]);
				$arrGeomInfo["ymin"]  = $y1     = trim($array[2]);
				$arrGeomInfo["xmax"] = $x2 = trim($array[3]);
				$arrGeomInfo["ymax"]  = $y2  = trim($array[4]);
				//$arrGeomInfo["objectsType"] = "LINE";
				//$arrGeomInfo["pointCount"]  = 2;
				//$arrGeomInfo["pointString"] = "$x1 $y1 $x2 $y2";

                $lineparser = new LineParser(false);
                $resultarray = $lineparser->parser($x1, $y1, $x2, $y2);
                $this->database->databaseInsertGeometry($this->tbname, $this->layerName, $this->layerName . "_" . $this->recordNumber, GeometryTypeLineString,
				    $resultarray[0], $resultarray[1], $resultarray[2], $resultarray[3], $resultarray[4], "", $this->SRSname, "", StyleDefault);

				if ($this->database->databaseGetErrorMessage() != "") {
                    $this->error = $this->database->databaseGetErrorMessage();
                }

                $this->recordNumber++;
			}

			//PLINE numpts1
            //x1 y1
            //x2 y2
            //x3 y3
			//pline 3
            //-89.045097 30.426008
            //-89.045181 30.425999
            //-89.045181 30.425999
			else if(substr($this->formatLine($line),0,5)=="PLINE")
			{
			    $count = trim(substr($line,6));
				#$array = explode(" ",$line);//$array[1]=point count
				$arrGeomInfo = $this->GetPointString($this->MIFFile,$count);
				//$arrGeomInfo["objectsType"] = "PLINE";
				//$arrGeometry[] = $arrGeomInfo;

                $PolylineParser = new PolylineParser(false,1);
                $resultarray = $PolylineParser->parser($arrGeomInfo["pointString"]);

                $this->database->databaseInsertGeometry($this->tbname, $this->layerName, $this->layerName . "_" . $this->recordNumber, GeometryTypeLineString,
				$resultarray[0], $resultarray[1], $resultarray[2], $resultarray[3], $resultarray[4], "", $this->SRSname, "", StyleDefault);

			    if ($this->database->databaseGetErrorMessage() != "") {
                    $this->error = $this->database->databaseGetErrorMessage();
                }

                $this->recordNumber++;
			}



/*
            REGION numpolygons
            numpts1
            x1 y1
            x2 y2
            :
            [ numpts2
            x1 y1
            x2 y2 ]
*/
			//Region  2(count)
            //2
            //-63.778904 46.515854
            //-63.790916 46.518894
            //3
            //-63.778904 46.515854
            //-63.790916 46.518894
            //-63.778904 46.515854
			else if(substr($this->formatLine($line),0,6)=="REGION")//Polygon/MultiPolygon
			{
			    //count how many sub region in the region
				$count = trim(substr($line,7));
				$countde = 0;
				$temp = array();
				while($count>0)
				{
					$line = trim(fgets($this->MIFFile, 1024));
					$arrGeomInfo = $this->GetPointString($this->MIFFile,$line);
					$arrGeomInfo["objectsType"] = "REGION";
					$arrGeometry[] = $arrGeomInfo;
					$temp[$countde]["pointString"] = $arrGeomInfo["pointString"];

					unset($arrGeomInfo);
					$count-=1;
					$countde++;
				}

				$multipolygonparser = new MultiPolygonParser(false);
                $resultarray = $multipolygonparser->parser($temp);
                $this->database->databaseInsertGeometry($this->tbname, $this->layerName, $this->layerName . "_" . $this->recordNumber, GeometryTypePolygon,
                $resultarray[0], $resultarray[1], $resultarray[2], $resultarray[3], $resultarray[4], "", $this->SRSname, "", StyleDefault);
                if ($this->database->databaseGetErrorMessage() != "") {
                    $this->error = $this->database->databaseGetErrorMessage();
                }
                $this->recordNumber++;
			}



			//ARC x1 y1 x2 y2
            //a b
            //An arc requires the diagonally opposite corners of its bounding rectangle
			//and the beginning (a) and ending (b) angles of the arc in degrees,
			//moving counter-clockwise with zero at three o'clock.
			else if(substr($this->formatLine($line),0,3)=="ARC")
			{
				$array = explode(" ",$line);
				$arrGeomInfo = array();
				$arrGeomInfo["xmin"] = $x1   = trim($array[1]);
				$arrGeomInfo["ymin"]  = $y1     = trim($array[2]);
				$arrGeomInfo["xmax"] = $x2 = trim($array[3]);
				$arrGeomInfo["ymax"]  = $y2  = trim($array[4]);
				$arrGeomInfo["objectsType"] = "ARC";
				$line = trim(fgets($this->MIFFile, 1024));
				$array_ab = explode(" ",$line);
				$a = $array_ab[0];
				$b = $array_ab[1];
				$arrGeomInfo["pointCount"]  = 2;
				$arrGeomInfo["pointString"] = "$x1 $y1 $x2 $y2 $a $b";
			}


            //TEXT "textstring"
            //x1 y1 x2 y2
            //Font ("Arial Cyr",0,0,0)
            //Angle 16.3

            //or

            //TEXT
            //"textstring"
            //x1 y1 x2 y2
            //[ FONT...]
            //[ Spacing {1.0 | 1.5 | 2.0}]
            //[ Justify {Left | Center | Right}]
            //[ Angle text_angle]
            //[ Label Line {simple | arrow} x y ]
		    else if(substr($this->formatLine($line),0,4)=="TEXT"){
		        $textString = trim(strtr(substr($line,5),'"',' '));//delete "
                //example 1
                if($textString!=""){
                    $textString = str_replace('\'', '\'\'', $textString);
                    $line = trim(fgets($this->MIFFile, 1024));
                    $array = explode(" ",$line);
                    $x1 = $arrGeomInfo["xmin"] = trim($array[0]);
                    $y1 = $arrGeomInfo["ymin"] = trim($array[1]);
                    $x2 = $arrGeomInfo["xmax"] = trim($array[2]);
                    $y2 = $arrGeomInfo["ymax"] = trim($array[3]);
				}
                //example 2
                else{
                    $line = trim(fgets($this->MIFFile, 1024));
                    $textString = trim(strtr($line,'"',' '));//delete "
                    $textString = str_replace('\'', '\'\'', $textString);
                    $line = trim(fgets($this->MIFFile, 1024));
                    $array = explode(" ",$line);
                    $x1 = $arrGeomInfo["xmin"] = trim($array[0]);
                    $y1 = $arrGeomInfo["ymin"] = trim($array[1]);
                    $x2 = $arrGeomInfo["xmax"] = trim($array[2]);
                    $y2 = $arrGeomInfo["ymax"] = trim($array[3]);
				}

				#$arrGeomInfo["objectsType"] = "TEXT";
				#$arrGeomInfo["textString"] = $textString;

				/* other way to parse
				//use only the first point
				$line  = trim(fgets($this->MIFFile, 1024));
		        $array = explode(" ",$line);
		        $x1 = $array[0];
		        $y2  = $array[1];
				$arrGeomInfo = array();
				$arrGeomInfo["xmin"] = $x1   = trim($array[1]);
				$arrGeomInfo["ymin"]  = $y1     = trim($array[2]);
				$arrGeomInfo["xmax"] = $x1 = trim($array[1]);
				$arrGeomInfo["ymax"]  = $y1  = trim($array[2]);
				$arrGeomInfo["pointCount"]  = 1;
				$arrGeomInfo["pointString"] = "$x1 $y1";
                */
                $attributes = writeTextAngleAttribute($textString,0);
                $this->textattribute = $attributes;

				$lineparser = new LineParser(false);
                $resultarray = $lineparser->parser($x1, $y1, $x2, $y2);
                $this->database->databaseInsertGeometry($this->tbname, $this->layerName, $this->layerName . "_" . $this->recordNumber, GeometryTypeText,
				    $resultarray[0], $resultarray[1], $resultarray[2], $resultarray[3], $resultarray[4], "", $this->SRSname, $attributes, StyleDefault);

                //for angle
                $this->textRecid = $this->layerName . "_" . $this->recordNumber;
                $this->textx1 = $x1;
                $this->texty1 = $y1;
                $this->textx2 = $x2;
                $this->texty2 = $y2;

                if ($this->database->databaseGetErrorMessage() != "") {
                    $this->error = $this->database->databaseGetErrorMessage();
                }
                $this->recordNumber++;
			}

			else if(substr($this->formatLine($line),0,5)=="ANGLE"){
                $angle = trim(substr($line,6));
                if($this->textRecid!=""){
                    $x1 = $this->textx1;
                    $y1 = $this->texty1;
                    $dis = sqrt(($this->textx2 - $this->textx1)*($this->textx2 - $this->textx1)+($this->texty2 - $this->texty1)*($this->texty2 - $this->texty1));
                    $x2 = $x1 + $dis * cos($angle);
                    $y2 = $y1 + $dis * sin($angle);
                    $lineparser = new LineParser(false);
                    $resultarray = $lineparser->parser($x1, $y1, $x2, $y2);

                    $attributes = addTextAngleAttribute($this->textattribute,$angle);
                    //use the original xmin ymin as text location, the bbox is maximum box
                    $this->database->updateTextAngle($this->tbname, $x1, $y1, $this->textx2, $this->texty2, $resultarray[4],$this->textRecid, $attributes);
                }

                $this->textRecid = "";
                $this->textattribute = "";
			}


			//RECT x1 y1 x2 y2  :coordinates of the diagonally opposite corners
			//or
			//ROUNDRECT x1 y1 x2 y2
            //a
            else if(substr($this->formatLine($line),0,4)=="RECT" OR substr($this->formatLine($line),0,9)=="ROUNDRECT")
			{
				$array = explode(" ",$line);
				$arrGeomInfo = array();
				$arrGeomInfo["xmin"] = $x1   = trim($array[1]);
				$arrGeomInfo["ymin"]  = $y1     = trim($array[2]);
				$arrGeomInfo["xmax"] = $x2 = trim($array[3]);
				$arrGeomInfo["ymax"]  = $y2  = trim($array[4]);
				//$arrGeomInfo["objectsType"] = "RECT";
				//$arrGeomInfo["pointCount"]  = 2;
				//$arrGeomInfo["pointString"] = "$x1 $y1 $x2 $y2";

				$ractangeparser = new RectangeParser(false);
                $resultarray = $ractangeparser->parser($x1, $y1, $x2-$x1, $y2-$y1);
                $this->database->databaseInsertGeometry($this->tbname, $this->layerName, $this->layerName . "_" . $this->recordNumber, GeometryTypeLineString,
				$resultarray[0], $resultarray[1], $resultarray[2], $resultarray[3], $resultarray[4], "", $this->SRSname, "", StyleDefault);

				if ($this->database->databaseGetErrorMessage() != "") {
                    $this->error = $this->database->databaseGetErrorMessage();
                }
                $this->recordNumber++;
			}
			//ELLIPSE x1 y1 x2 y2
			//coordinates of the diagonally opposite corners of its bounding rectangle
			//use as point
            else if(substr($this->formatLine($line),0,7)=="ELLIPSE")
			{
				$array = explode(" ",$line);
				$arrGeomInfo = array();
				$arrGeomInfo["xmin"] = $x1   = trim($array[1]);
				$arrGeomInfo["ymin"]  = $y1     = trim($array[2]);
				$arrGeomInfo["xmax"] = $x2 = trim($array[3]);
				$arrGeomInfo["ymax"]  = $y2 = trim($array[4]);
				#$arrGeomInfo["objectsType"] = "Point";
				#$arrGeomInfo["pointCount"]  = 1;
				#$arrGeomInfo["pointString"] = "$x1 $y1 $x2 $y2";

				$pointparser = new PointParser(false);
                $resultarray = $pointparser->parser( ($x1+$x2)/2, ($y1+$y2)/2);
                $this->database->databaseInsertGeometry($this->tbname, $this->layerName, $this->layerName . "_" . $this->recordNumber, GeometryTypePoint,
				$resultarray[0], $resultarray[1], $resultarray[2], $resultarray[3], $resultarray[4], "", $this->SRSname, "", StyleDefault);

				if ($this->database->databaseGetErrorMessage() != "") {
                    $this->error = $this->database->databaseGetErrorMessage();
                }
                $this->recordNumber++;
			}

			if(isset($arrGeomInfo))
			{
				$arrGeometry[] = $arrGeomInfo;
				unset($arrGeomInfo);
			}
		}
	}

   	#print_r($arrGeometry);
	#return $arrGeometry;
}

function GetPointString($MIFFile,$point_count)
{
	$arrGeomInfoTemp = array();
	$arrGeomInfoTemp["xmin"]    = 9999999;
	$arrGeomInfoTemp["ymin"]     = 9999999;
	$arrGeomInfoTemp["xmax"]    = -9999999;
	$arrGeomInfoTemp["ymax"]     = -9999999;
	$arrGeomInfoTemp["pointString"] = "";
	$arrGeomInfoTemp["pointCount"] = $point_count;

	for($i=0;$i<$arrGeomInfoTemp["pointCount"];$i++)
	{
		$line  = trim(fgets($this->MIFFile, 1024));
		$array = explode(" ",$line);
		$x = $array[0];
		$y  = $array[1];
        //if i=0 $arrGeomInfoTemp["xmin"]=x;
		$arrGeomInfoTemp["xmin"]  = min($x,$arrGeomInfoTemp["xmin"]);
		$arrGeomInfoTemp["ymin"]   = min($y ,$arrGeomInfoTemp["ymin"]);
		$arrGeomInfoTemp["xmax"]  = max($x,$arrGeomInfoTemp["xmax"]);
		$arrGeomInfoTemp["ymax"]   = max($y ,$arrGeomInfoTemp["ymax"]);
		if(!empty($arrGeomInfoTemp["pointString"]))$arrGeomInfoTemp["pointString"] .= " ";
		$arrGeomInfoTemp["pointString"] .= "$x $y";
	}

	return $arrGeomInfoTemp;
}

    function setError($error_)
    {
        $this->error = $error_;
    }
    public function getError(){
	    return $this->error;
	}
    function setLog($log_){
	    $this->log .= $log_;
	}
	public function getLog(){
		return $this->log;
	}
}
?>