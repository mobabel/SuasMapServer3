<?php

require_once 'Geometry2WKT.class.php';
require_once '../Models/Setting.class.php';
require_once 'AttributeParser.class.php';

class SVGToDB {
    // ==================================================
    // ==================================================
    private $parser;
    private $con;
    private $table;
    private $recordset = array();
    private $content;
    private $value;
    private $field;
    private $bRead;
    private $attr_tag;

    private $servername, $username, $password, $dbname, $tbname, $fileName, $SRSname;
    public $error = "";
    public $log = ""; //for using getLog4Databae
    public $recordgood = 0;  //record number that inserted successfully
    public $recordbad = 0;
    // ==================================================
    public $db_layer = "LayerNotDefined";
    public $db_recid = 0;

    public $db_GSTYLE;
    public $db_GFILL;
    public $db_GSTROKE;
    public $db_GSTROKE_WIDTH;
    public $db_GTEXT_FONT_FAMILY;
    public $db_GTEXT_FONT_SIZE;

    public $xml_errorstring;
    // Point Geometry
    public $db_GeoCIRCLE_ID;
    public $db_GeoCIRCLE_CX;
    public $db_GeoCIRCLE_CY;
    public $db_GeoCIRCLE_R;
    public $db_GeoCIRCLE_FILL;
    public $db_GeoCIRCLE_STROKE;
    public $db_GeoCIRCLE_STROKE_WIDTH;
    public $db_GeoCIRCLE_STYLE;

    public $db_GeoELLIPSE_ID;
    public $db_GeoELLIPSE_CX;
    public $db_GeoELLIPSE_CY;
    public $db_GeoELLIPSE_RX;
    public $db_GeoELLIPSE_RY;
    public $db_GeoELLIPSE_FILL;
    public $db_GeoELLIPSE_STROKE;
    public $db_GeoELLIPSE_STROKE_WIDTH;
    public $db_GeoELLIPSE_STYLE;
    // LineString Geometry
    public $db_GeoLINE_ID;
    public $db_GeoLINE_X1;
    public $db_GeoLINE_Y1;
    public $db_GeoLINE_X2;
    public $db_GeoLINE_Y2;
    public $db_GeoLINE_FILL;
    public $db_GeoLINE_STROKE;
    public $db_GeoLINE_STROKE_WIDTH;
    public $db_GeoLINE_STYLE;

    public $db_GeoRECT_ID;
    public $db_GeoRECT_X;
    public $db_GeoRECT_Y;
    public $db_GeoRECT_WIDTH;
    public $db_GeoRECT_HEIGHT;
    public $db_GeoRECT_FILL;
    public $db_GeoRECT_STROKE;
    public $db_GeoRECT_STROKE_WIDTH;
    public $db_GeoRECT_STYLE;

    public $db_GeoIMAGE_ID;
    public $db_GeoIMAGE_X;
    public $db_GeoIMAGE_Y;
    public $db_GeoIMAGE_WIDTH;
    public $db_GeoIMAGE_HEIGHT;
    public $db_GeoIMAGE_XLINKHREF;

    public $db_GeoPATH_ID;
    public $db_GeoPATH_D;
    public $db_GeoPATH_FILL;
    public $db_GeoPATH_STROKE;
    public $db_GeoPATH_STYLE;
    public $db_GeoPATH_STROKE_WIDTH;

    public $db_GeoPOLYLINE_ID;
    public $db_GeoPOLYLINE_POINTS;
    public $db_GeoPOLYLINE_FILL;
    public $db_GeoPOLYLINE_STROKE;
    public $db_GeoPOLYLINE_STYLE;
    public $db_GeoPOLYLINE_STROKE_WIDTH;
    // Polygon Geometry
    public $db_GeoPOLYGON_ID;
    public $db_GeoPOLYGON_POINTS;
    public $db_GeoPOLYGON_FILL;
    public $db_GeoPOLYGON_STROKE;
    public $db_GeoPOLYGON_STYLE;
    public $db_GeoPOLYGON_STROKE_WIDTH;
    // Text Geometry
    public $text_tag;
    public $db_GeoTEXT_ID;
    public $db_GeoTEXT_X;
    public $db_GeoTEXT_Y;
    public $db_GeoTEXT_CONTENT;
    public $db_GeoTEXT_FILL;
    public $db_GeoTEXT_STROKE;
    public $db_GeoTEXT_FONT_FAMILY;
    public $db_GeoTEXT_FONT_SIZE;
    public $db_GeoTEXT_STYLE;

    public $database;

    function init()
    {
        $this->roottag = "";
        $this->curtag = &$this->roottag;
    }
    // ==================================================
    function SVGToDB($servername_, $username_, $password_, $dbname_, $tbname_, $fileName_, $SRSname_)
    {

        $this->servername = $servername_;
        $this->username = $username_;
        $this->password = $password_;
        $this->dbname = $dbname_;
        $this->tbname = $tbname_;
        $this->fileName = $fileName_;
        $this->SRSname = $SRSname_;

        $database = new Database();
        $this->database =$database;
        $this->database->databaseConfig($this->servername, $this->username, $this->password, $this->dbname);
        $this->database->databaseConnect();

        $this->init();

        $this->parser = xml_parser_create("UTF-8");
        xml_set_object($this->parser, $this);
        xml_set_element_handler($this->parser, "tag_open", "tag_close");
        xml_set_character_data_handler($this->parser, "cdata");

        if (file_exists($this->fileName)) {
        //begin to parse
            $xml_file = @fopen($this->fileName, 'r');
            if($xml_file){
                while (($data = fread($xml_file, 4194304))) {
                    $this->parse($data);
                }
                fclose($xml_file);
            }
            else{
			    return $this->error = "It is not possible to open the SVG file".$this->file_name;
			}
        }
        else{
            return $this->error = "The SVG file $this->file_name does not exist";
		}
    }

    function parse($data)
    {
        @xml_parse($this->parser, $data) or
        die(sprintf("
		<h1>Failure</h1>
			<p id=\"intro\">You must correct the error below before installation can continue:<br /><br />
			<span style=\"color:#000000\">XML Error: %s at line %d, please check the SVG file!</span><br /><br />
			<a href=\"javascript: history.go(-1)\">Click here to go back</a>.</p>
	  </div>
	</td>
</tr>
</table>
</body>
</html>
",
                xml_error_string(xml_get_error_code($this->parser)),
                xml_get_current_line_number($this->parser))
            );

      $this->recordgood = $this->database->recordgood;
      $this->recordbad = $this->database->recordbad;
      $this->log = $this->database->getLog4Database();
    }
    // function parse($data) {
    //  @xml _parse($this->parser, $data) or
    // die(sprintf("XML Error: %s at line %d",
    // xml_error_string(xml_get_error_code($this->parser)),
    // xml_get_current_line_number($this->parser)));
    // }
    function tag_open($parser, $tag, $attributes)
    {
        $parser;
        // echo "s2"."\n";
        if (!is_object($this->curtag)) {
            $null = 0;
            // $this->curtag = new XMLTag($null);
            // $this->curtag->set_name( $tag );
            // $this->curtag->set_attributes( $attributes );
        } else { // otherwise, add it to the tag list and move curtag
            // $this->curtag->add_subtag( $tag, $attributes );
            // $this->curtag = &$this->curtag->curtag;
        }
        switch ($tag) {
            case 'SVG': break;
            case 'SYMBOL': break;
            case 'G': { // Group
                    $this->attr_tag = $tag;
                    $this->db_layer = $attributes['ID'];
                    $this->db_GSTYLE = $attributes['STYLE'];
                    $this->db_GFILL = $attributes['FILL'];
                    $this->db_GSTROKE = $attributes['STROKE'];
                    $this->db_GSTROKE_WIDTH = $attributes['STROKE-WIDTH'];
                    $this->db_GTEXT_FONT_FAMILY = $attributes['FONT-FAMILY'];
                    $this->db_GTEXT_FONT_SIZE = $attributes['FONT-SIZE'];
                    $this->bRead = true;
                }
                break; //in <G>
            // /////////////////////////////////////////////////////////////////
            case 'CIRCLE': {
                    $this->attr_tag = $tag;
                    $this->db_GeoCIRCLE_ID = $attributes['ID'];
                    $this->db_GeoCIRCLE_CX = $attributes['CX'];
                    $this->db_GeoCIRCLE_CY = $attributes['CY'];
                    $this->db_GeoCIRCLE_R = $attributes['R'];
                    $this->db_GeoCIRCLE_FILL = $attributes['FILL'];
                    $this->db_GeoCIRCLE_STROKE = $attributes['STROKE'];
                    $this->db_GeoCIRCLE_STROKE_WIDTH = $attributes['STROKE-WIDTH'];
                    $this->db_GeoCIRCLE_STYLE = $attributes['STYLE'];
                    $this->recordset = array();
                }
                break;
            case 'PATH': {
                    $this->attr_tag = $tag;
                    $this->db_GeoPATH_ID = $attributes['ID'];
                    $this->db_GeoPATH_D = $attributes['D'];
                    $this->db_GeoPATH_FILL = $attributes['FILL'];
                    $this->db_GeoPATH_STROKE = $attributes['STROKE'];
                    $this->db_GeoPATH_STYLE = $attributes['STYLE'];
                    $this->db_GeoPATH_STROKE_WIDTH = $attributes['STROKE-WIDTH'];
                    $this->recordset = array();
                    // These are some spaces in the path, but the number is unclear, they must be delete
                    $iSpace = iSpace;
                    // $iSpace=strlen($pathString);
                    for($iSpace;$iSpace > 0;$iSpace--) {
                        $strChars = " ";
                        for($iaddS = 0;$iaddS < $iSpace;$iaddS++) {
                            $strChars = $strChars . " ";
                        }
                        $this->db_GeoPATH_D = str_replace($strChars, ' ', $this->db_GeoPATH_D);
                    }
                }
                break;
            case 'TEXT': {
                    $this->text_tag = $tag;
                    $this->db_GeoTEXT_ID = $attributes['ID'];
                    $this->db_GeoTEXT_X = $attributes['X'];
                    $this->db_GeoTEXT_Y = $attributes['Y'];
                    $this->db_GeoTEXT_FILL = $attributes['FILL'];
                    $this->db_GeoTEXT_STROKE = $attributes['STROKE'];
                    $this->db_GeoTEXT_FONT_FAMILY = $attributes['FONT-FAMILY'];
                    $this->db_GeoTEXT_FONT_SIZE = $attributes['FONT-SIZE'];
                    $this->db_GeoTEXT_STYLE = $attributes['STYLE'];
                    $this->db_GeoTEXT_CONTENT = (isset($attributes['CONTENT'])) ? $attributes['CONTENT'] : '';
                    $this->recordset = array();
                }
                break;
            case 'RECT': {
                    $this->attr_tag = $tag;
                    $this->db_GeoRECT_ID = $attributes['ID'];
                    $this->db_GeoRECT_X = $attributes['X'];
                    $this->db_GeoRECT_Y = $attributes['Y'];
                    $this->db_GeoRECT_WIDTH = $attributes['WIDTH'];
                    $this->db_GeoRECT_HEIGHT = $attributes['HEIGHT'];
                    $this->db_GeoRECT_FILL = $attributes['FILL'];
                    $this->db_GeoRECT_STROKE = $attributes['STROKE'];
                    $this->db_GeoRECT_STROKE_WIDTH = $attributes['STROKE-WIDTH'];
                    $this->db_GeoRECT_STYLE = $attributes['STYLE'];
                    $this->recordset = array();
                }
                break;
            case 'IMAGE': {
                    $this->attr_tag = $tag;
                    $this->db_GeoIMAGE_ID = $attributes['ID'];
                    $this->db_GeoIMAGE_X = $attributes['X'];
                    $this->db_GeoIMAGE_Y = $attributes['Y'];
                    $this->db_GeoIMAGE_WIDTH = $attributes['WIDTH'];
                    $this->db_GeoIMAGE_HEIGHT = $attributes['HEIGHT'];
                    $this->db_GeoIMAGE_XLINKHREF = $attributes['XLINK:HREF'];
                    $this->recordset = array();
                }
                break;
            case 'ELLIPSE': {
                    $this->attr_tag = $tag;
                    $this->db_GeoELLIPSE_ID = $attributes['ID'];
                    $this->db_GeoELLIPSE_CX = $attributes['CX'];
                    $this->db_GeoELLIPSE_CY = $attributes['CY'];
                    $this->db_GeoELLIPSE_RX = $attributes['RX'];
                    $this->db_GeoELLIPSE_RY = $attributes['RY'];
                    $this->db_GeoELLIPSE_FILL = $attributes['FILL'];
                    $this->db_GeoELLIPSE_STROKE = $attributes['STROKE'];
                    $this->db_GeoELLIPSE_STROKE_WIDTH = $attributes['STROKE-WIDTH'];
                    $this->db_GeoELLIPSE_STYLE = $attributes['STYLE'];
                    $this->recordset = array();
                }
                break;
            case 'LINE': {
                    $this->attr_tag = $tag;
                    $this->db_GeoLINE_ID = $attributes['ID'];
                    $this->db_GeoLINE_X1 = $attributes['X1'];
                    $this->db_GeoLINE_Y1 = $attributes['Y1'];
                    $this->db_GeoLINE_X2 = $attributes['X2'];
                    $this->db_GeoLINE_Y2 = $attributes['Y2'];
                    $this->db_GeoLINE_FILL = $attributes['FILL'];
                    $this->db_GeoLINE_STROKE = $attributes['STROKE'];
                    $this->db_GeoLINE_STROKE_WIDTH = $attributes['STROKE-WIDTH'];
                    $this->db_GeoLINE_STYLE = $attributes['STYLE'];
                    $this->recordset = array();
                }
                break;
            case 'POLYGON': {
                    $this->attr_tag = $tag;
                    $this->db_GeoPOLYGON_ID = $attributes['ID'];
                    $this->db_GeoPOLYGON_POINTS = $attributes['POINTS'];
                    $this->db_GeoPOLYGON_FILL = $attributes['FILL'];
                    $this->db_GeoPOLYGON_STROKE = $attributes['STROKE'];
                    $this->db_GeoPOLYGON_STROKE_WIDTH = $attributes['STROKE-WIDTH'];
                    $this->db_GeoPOLYGON_STYLE = $attributes['STYLE'];
                    $this->recordset = array();
                }
                break;
            case 'POLYLINE': {
                    $this->attr_tag = $tag;
                    $this->db_GeoPOLYLINE_ID = $attributes['ID'];
                    $this->db_GeoPOLYLINE_POINTS = $attributes['POINTS'];
                    $this->db_GeoPOLYLINE_FILL = $attributes['FILL'];
                    $this->db_GeoPOLYLINE_STROKE = $attributes['STROKE'];
                    $this->db_GeoPOLYLINE_STROKE_WIDTH = $attributes['STROKE-WIDTH'];
                    $this->db_GeoPOLYLINE_STYLE = $attributes['STYLE'];
                    $this->recordset = array();
                }
        }
    }

    function cdata($parser, $cdata)
    {
        $parser;
        switch ($this->content) {
            case 'base64': {
                    $this->value = base64_decode($cdata);
                }
                break;
            default: {
                    $this->value = $cdata;
                }
                $this->db_GeoTEXT_CONTENT = $this->value;
        }
    }

    function tag_close($parser, $tag)
    {
        $parser;
        // echo "s3"."\n";
        switch ($tag) {
            case 'SVG': break;
            case 'SYMBOL': break;
            case 'G': {
                    $this->bRead = false;
                }
                break;
            // -----------------------CIRCLE------------------------------------------------
            case 'CIRCLE': {
                    if ($this->bRead == true) {
                        $this->recordset[$this->field] = $this->value;
                        if($this->db_layer == ""){
                            $this->db_layer = "LayerNotDefined";
						}

                        $pointparser = new PointParser(true);
                        $resultarray = $pointparser->parser($this->db_GeoCIRCLE_CX, $this->db_GeoCIRCLE_CY);
                        $this->database->databaseInsertGeometry($this->tbname, $this->db_layer, $this->db_GeoCIRCLE_ID, GeometryTypePoint, $resultarray[0], $resultarray[1], $resultarray[2], $resultarray[3], $resultarray[4], "", $this->SRSname, "", StyleDefault);

					if ($this->database->databaseGetErrorMessage() != "") {
                        $this->error = $this->database->databaseGetErrorMessage();
                        break;
                    }

					}
                }
                break;
            // -----------------------ELLIPSE------------------------------------------------
            case 'ELLIPSE': {
                    if ($this->bRead == true) {
                        $this->recordset[$this->field] = $this->value;
                        if($this->db_layer == ""){
                            $this->db_layer = "LayerNotDefined";
						}

                        $pointparser = new PointParser(true);
                        $resultarray = $pointparser->parser($this->db_GeoELLIPSE_CX, $this->db_GeoELLIPSE_CY);
                        $this->database->databaseInsertGeometry($this->tbname, $this->db_layer, $this->db_GeoELLIPSE_ID, GeometryTypePoint, $resultarray[0], $resultarray[1], $resultarray[2], $resultarray[3], $resultarray[4], "", $this->SRSname, "", StyleDefault);

					if ($this->database->databaseGetErrorMessage() != "") {
                        $this->error = $this->database->databaseGetErrorMessage();
                        break;
                    }
					}
                }
                break;
            // -----------------------LINE------------------------------------------------
            case 'LINE': {
                    if ($this->bRead == true) {
                        $this->recordset[$this->field] = $this->value;
                        if($this->db_layer == ""){
                            $this->db_layer = "LayerNotDefined";
						}

                        $lineparser = new LineParser(true);
                        $resultarray = $lineparser->parser($this->db_GeoLINE_X1, $this->db_GeoLINE_Y1, $this->db_GeoLINE_X2, $this->db_GeoLINE_Y2);
                        $this->database->databaseInsertGeometry($this->tbname, $this->db_layer, $this->db_GeoLINE_ID, GeometryTypeLineString, $resultarray[0], $resultarray[1], $resultarray[2], $resultarray[3], $resultarray[4], "", $this->SRSname, "", StyleDefault);

					if ($this->database->databaseGetErrorMessage() != "") {
                        $this->error = $this->database->databaseGetErrorMessage();
                        break;
                    }
					}
                }
                break;
            // -----------------------RECTANGE------------------------------------------------
            case 'RECT': {
                    if ($this->bRead == true) {
                        $this->recordset[$this->field] = $this->value;
                        if($this->db_layer == ""){
                            $this->db_layer = "LayerNotDefined";
						}

                        $ractangeparser = new RectangeParser(true);
                        $resultarray = $ractangeparser->parser($this->db_GeoRECT_X, $this->db_GeoRECT_Y, $this->db_GeoRECT_WIDTH, $this->db_GeoRECT_HEIGHT);
                        $this->database->databaseInsertGeometry($this->tbname, $this->db_layer, $this->db_GeoRECT_ID, GeometryTypeLineString, $resultarray[0], $resultarray[1], $resultarray[2], $resultarray[3], $resultarray[4], "", $this->SRSname, "", StyleDefault);

					if ($this->database->databaseGetErrorMessage() != "") {
                        $this->error = $this->database->databaseGetErrorMessage();
                        break;
                    }
					}
                }
                break;
            // -----------------------RECTANGE------------------------------------------------
            case 'IMAGE': {
                    if ($this->bRead == true) {
                        $this->recordset[$this->field] = $this->value;
                        if($this->db_layer == ""){
                            $this->db_layer = "LayerNotDefined";
						}

                        $ractangeparser = new RectangeParser(true);
                        $resultarray = $ractangeparser->parser($this->db_GeoIMAGE_X, $this->db_GeoIMAGE_Y, $this->db_GeoIMAGE_WIDTH, $this->db_GeoIMAGE_HEIGHT);
                        if ($this->db_GeoIMAGE_XLINKHREF != "") {
                            $this->database->databaseInsertGeometry($this->tbname, $this->db_layer, $this->db_GeoIMAGE_ID, GeometryTypeImage, $resultarray[0], $resultarray[1], $resultarray[2], $resultarray[3], $resultarray[4], $this->db_GeoIMAGE_XLINKHREF, $this->SRSname, "", StyleDefault);
                        }

                    if ($this->database->databaseGetErrorMessage() != "") {
                        $this->error = $this->database->databaseGetErrorMessage();
                        break;
                    }
					}
                }
                break;
            // -----------------------PATH------------------------------------------------
            /*
* M = moveto
* L = lineto
* H = horizontal lineto
* V = vertical lineto
* C = curveto
* S = smooth curveto
* Q = quadratic Belzier curve
* T = smooth quadratic Belzier curveto
* A = elliptical Arc
* Z = closepath
*/
            case 'PATH': {
                    if ($this->bRead == true) {
                        $this->recordset[$this->field] = $this->value;
                        if($this->db_layer == ""){
                            $this->db_layer = "LayerNotDefined";
						}

                        // $db_GeoPATH_D_uppercase is $db_Gpdu
                        $db_Gpdu = strtoupper($this->db_GeoPATH_D);
                        // if path(s) is close path(s), the other commands such as H,V.. are not considered here
                        if (strstr($db_Gpdu, 'M') AND strstr($db_Gpdu, 'L') AND !strstr($db_Gpdu, 'H') AND !strstr($db_Gpdu, 'V') AND !strstr($db_Gpdu, 'C') AND !strstr($db_Gpdu, 'S') AND !strstr($db_Gpdu, 'Q') AND !strstr($db_Gpdu, 'T') AND !strstr($db_Gpdu, 'A')) {
                            $path_mlzparser = new Path_MLZParser(true);
                            $resultarray = $path_mlzparser->parser($this->db_GeoPATH_D);
                        }
                        // The other commands such as H,V..
                        // The XY max min here is not accurate, need update later
                        elseif (strstr($db_Gpdu, 'H') OR strstr($db_Gpdu, 'V') OR strstr($db_Gpdu, 'C') OR strstr($db_Gpdu, 'S') OR strstr($db_Gpdu, 'Q') OR strstr($db_Gpdu, 'T') OR strstr($db_Gpdu, 'A')) {
                            break;
                        } //
                        $this->database->databaseInsertGeometry($this->tbname, $this->db_layer, $this->db_GeoPATH_ID, GeometryTypeLineString, $resultarray[0], $resultarray[1], $resultarray[2], $resultarray[3], $resultarray[4], "", $this->SRSname, "", StyleDefault);

					if ($this->database->databaseGetErrorMessage() != "") {
                        $this->error = $this->database->databaseGetErrorMessage();
                        break;
                    }
					}
                }
                break;
            // -----------------------POLYLINE------------------------------------------------
            case 'POLYLINE': {
                    if ($this->bRead == true) {
                        $this->recordset[$this->field] = $this->value;
                        if($this->db_layer == ""){
                            $this->db_layer = "LayerNotDefined";
						}

                        $PolylineParser = new PolylineParser(true,0);
                        $resultarray = $PolylineParser->parser($this->db_GeoPOLYLINE_POINTS);

                        $this->database->databaseInsertGeometry($this->tbname, $this->db_layer, $this->db_GeoPOLYLINE_ID, GeometryTypeLineString, $resultarray[0], $resultarray[1], $resultarray[2], $resultarray[3], $resultarray[4], "", $this->SRSname, "", StyleDefault);

				    if ($this->database->databaseGetErrorMessage() != "") {
                        $this->error = $this->database->databaseGetErrorMessage();
                        break;
                    }
					}
                }
                break;
            // -----------------------POLYGON------------------------------------------------
            case 'POLYGON': {
                    if ($this->bRead == true) {
                        $this->recordset[$this->field] = $this->value;
                        if($this->db_layer == ""){
                            $this->db_layer = "LayerNotDefined";
						}

                        $PolygonParser = new PolygonParser(true);
                        $resultarray = $PolygonParser->parser($this->db_GeoPOLYGON_POINTS);

                        $this->database->databaseInsertGeometry($this->tbname, $this->db_layer, $this->db_GeoPOLYGON_ID, GeometryTypePolygon, $resultarray[0], $resultarray[1], $resultarray[2], $resultarray[3], $resultarray[4], "", $this->SRSname, "", StyleDefault);

					if ($this->database->databaseGetErrorMessage() != "") {
                        $this->error = $this->database->databaseGetErrorMessage();
                        break;
                    }
					}
                }
                break;
            // -----------------------TEXT------------------------------------------------
            case 'TEXT': {
                    if ($this->bRead == true) {
                        $this->recordset[$this->field] = $this->value;
                        $this->db_GeoTEXT_CONTENT = str_replace('\'', '\'\'', $this->db_GeoTEXT_CONTENT);
                        $attributes = writeTextAngleAttribute($this->db_GeoTEXT_CONTENT,0);

                        if($this->db_layer == ""){
                            $this->db_layer = "LayerNotDefined";
						}
                        //delete the px mark in element!
                        $this->db_GeoTEXT_X = trim(str_replace('PX','',strtoupper($this->db_GeoTEXT_X)));
                        $this->db_GeoTEXT_Y = trim(str_replace('PX','',strtoupper($this->db_GeoTEXT_Y)));

						$pointparser = new PointParser(true);
                        $resultarray = $pointparser->parser($this->db_GeoTEXT_X, $this->db_GeoTEXT_Y);
                        $this->database->databaseInsertGeometry($this->tbname, $this->db_layer, $this->db_GeoTEXT_ID, GeometryTypeText, $resultarray[0], $resultarray[1], $resultarray[2], $resultarray[3], $resultarray[4], "", $this->SRSname, $attributes, StyleDefault);

                    if ($this->database->databaseGetErrorMessage() != "") {
                        $this->error = $this->database->databaseGetErrorMessage();
                        break;
                    }
                    }
                }
                // -----------------------ALL SVG SHAPES------------------------------------------------
        } //switch tag
    }

}
?>